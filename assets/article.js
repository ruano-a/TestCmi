import ApiService from './common/ApiService.js';
import ViewService from './common/ViewService.js';

import './styles/article.css';

const articleBaseUrl = ApiService.getApiBaseUrl() + 'article/';
const commentsBaseUrl = ApiService.getApiBaseUrl() + 'comment/';
const postCommentUrl = ApiService.getApiBaseUrl() + 'comment/post';
const voteCommentUrl = ApiService.getApiBaseUrl() + 'comment/vote';


var commentSending = false;

function createCommentBlock(commentData, level, sendCommentArea) {
	const container = document.createElement("div");
	const moveRight = level * 30;
	container.className = 'article-comment block-container';

	container.style = 'margin-left: ' + moveRight + 'px; width: calc(100% - ' + moveRight + 'px)';
	container.innerHTML = '<div class="comment-left-part">'
							+ '<div class="comment-author">' + commentData.createdByUser.username + '</div>'
							+ '<div class="comment-score">' + commentData.score + '</div>'
							+ (sendCommentArea ? '<div class="comment-vote"><span class="comment-vote-plus">+</span><span class="comment-vote-minus">-</span></div>' : '')
							+ '</div>'
							+ '<div class="comment-right-part">'
								+ '<div class="comment-content">' + commentData.text + '</div>'
								+ '<div class="comment-date">' + ViewService.formatDate(commentData.creationDate) + '</div>'
								+ (sendCommentArea ? '<a class="comment-answer" href="#">Answer</a>' : '')
							+ '</div>';
	container.setAttribute('data-id', commentData.id);
	return container;
}

function sortCommentsByParent(comments) {
	const result = [];

	comments.forEach(comment => {
		comment.children = [];
		if (!comment.parentComment) {
			result.push(comment);
		}
		else {
			const parentId = comment.parentComment.id; // I can't use comment.parentComment, it's an independant object
			parent = comments.find(elem => elem.id === parentId);
			if (!parent) // can't normally happen
				console.error('comment ' + parentId + 'not found');
			parent.children.push(comment);
		}
	});

	return result;
}

function sendVote(commentId, value) {
	ApiService.post(voteCommentUrl, {commentId: commentId, vote: value}, (result) => {
		console.log("Success:", result);
		if (result.result === 'ok')
			window.location.reload();
		else
			window.alert(result.message);
	},
	(error) => {
		console.error(error);
		window.alert(error);
	});
}

function addCommentsWaterfall(commentContainer, sortedComments, level) {
	const sendCommentArea = document.getElementById('send-comment-area');

	sortedComments.forEach(commentData => {
		const newComment = createCommentBlock(commentData, level, sendCommentArea);
		if (sendCommentArea) {
			const answerLink = newComment.getElementsByClassName('comment-answer');
			const upvoteLink = newComment.getElementsByClassName('comment-vote-plus');
			const downvoteLink = newComment.getElementsByClassName('comment-vote-minus');
			const answerDescription = document.getElementById('answer-description');
			const id = commentData.id;
			answerLink[0].addEventListener('click', (e) => {
				e.preventDefault();
				const commentParent = document.getElementById('send-comment-parent');
				commentParent.value = id;
				const contentNode = e.target.parentNode.getElementsByClassName('comment-content')[0];
				answerDescription.innerHTML = 'Answer to "' + contentNode.innerHTML + '"';
			});
			upvoteLink[0].addEventListener('click', (e) => {
				e.preventDefault();
				sendVote(id, 1);
			})
			downvoteLink[0].addEventListener('click', (e) => {
				e.preventDefault();
				sendVote(id, -1);
			})
		}
		commentContainer.appendChild(newComment);
		addCommentsWaterfall(commentContainer, commentData.children, level + 1);
	});
}

function loadComments(articleId) {
	const commentsButton = document.getElementById('load-comments');
	const sendCommentArea = document.getElementById('send-comment-area');

	ApiService.get(commentsBaseUrl + articleId, (result) => {
		const commentContainer = document.getElementById('article-comments')
		const sortedComments = sortCommentsByParent(result);
		addCommentsWaterfall(commentContainer, sortedComments, 0);
		commentsButton.remove();
		if (sendCommentArea)
			sendCommentArea.removeAttribute('style');
	},
	(error) => {
		console.error(error);
		window.alert(error);
		commentsButton.innerHTML = 'Load comments';
	});
}

function sendComment() {
	commentSending = true;
	grecaptcha.ready(function() {
		const commentRecaptcha = document.getElementById('comment-recaptcha');
		grecaptcha.execute(commentRecaptcha.getAttribute('data-site-key'), {action: 'post_comment'}).then(function(token) {
			commentRecaptcha.value = token;
			const form = document.getElementById('send-comment-area');
			const data = new URLSearchParams();
			for (const pair of new FormData(form)) {
			    data.append(pair[0], pair[1]);
			}
			ApiService.postForm(postCommentUrl, data, (result) => {
				console.log("Success:", result);
				if (result.result === 'ok')
					window.location.reload();
				else
					window.alert(result.message);
			},
			(error) => {
				console.error(error);
				window.alert(error);
			},
			() => {
				commentSending = false;
			});
		});
	});
}

document.addEventListener('DOMContentLoaded', function() {
	const id = document.getElementById('article').getAttribute('data-id');
	const titleContainer = document.getElementById('article-title');
	const contentContainer = document.getElementById('article-content');
	const dateContainer = document.getElementById('article-date');
	const authorContainer = document.getElementById('article-author');
	const commentsButton = document.getElementById('load-comments');
	const sendCommentButton = document.getElementById('send-comment-button');
	const sendCommentInput = document.getElementById('send-comment-input');
	const form = document.getElementById('send-comment-area');

	ApiService.get(articleBaseUrl + id, (result) => {
		titleContainer.innerHTML = result.title;
		contentContainer.innerHTML = result.content;
		dateContainer.innerHTML = ViewService.formatDate(result.creationDate);
		authorContainer.innerHTML = ' redacted by ' + result.createdByUser.username;
	},
	(error) => {
		console.error(error);
		window.alert(error);
	});

	commentsButton.addEventListener('click', () => {
		commentsButton.innerHTML = 'Loading';
		loadComments(id);
	});
	if (sendCommentButton) {
		sendCommentButton.addEventListener('click', (e) => {
			e.preventDefault();
			if (!form.reportValidity())
				return;
			sendComment(sendCommentInput.value);
		});
	}
});