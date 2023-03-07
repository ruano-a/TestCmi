import ApiService from './common/ApiService.js';
import ViewService from './common/ViewService.js';

import './styles/article.css';

const articleBaseUrl = ApiService.getApiBaseUrl() + 'article/';
const commentsBaseUrl = ApiService.getApiBaseUrl() + 'comment/';
const postCommentUrl = ApiService.getApiBaseUrl() + 'comment/post';


var commentSending = false;

function createCommentBlock(commentData, level, sendCommentArea) {
	const container = document.createElement("div");
	const moveRight = level * 30;
	container.className = 'article-comment';

	container.style = 'margin-left: ' + moveRight + 'px; width: calc(100% - ' + moveRight + 'px)';
	container.innerHTML = '<div class="comment-author">' + commentData.createdByUser.username + '</div>'
							+ '<div class="comment-right-part">'
								+ '<div class="comment-content">' + commentData.text + '</div>'
								+ '<div class="comment-date">' + ViewService.formatDate(commentData.creationDate) + '</div>'
								+ (sendCommentArea ? '<a class="comment-answer" data-id="'+ commentData.id +'" href="#">Answer</a>' : '')
							+ '</div>';
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

function addCommentsWaterfall(commentContainer, sortedComments, level) {
	const sendCommentArea = document.getElementById('send-comment-area');

	sortedComments.forEach(commentData => {
		const newComment = createCommentBlock(commentData, level, sendCommentArea);
		const answerLink = newComment.getElementsByClassName('comment-answer');
		if (answerLink) {
			answerLink[0].addEventListener('click', (e) => {
				e.preventDefault();
				const commentParent = document.getElementById('send-comment-parent');
				commentParent.value = e.target.getAttribute('data-id');
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
		console.log(sortedComments);
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
	const form = document.getElementById('send-comment-area');
	const data = new URLSearchParams();
	for (const pair of new FormData(form)) {
	    data.append(pair[0], pair[1]);
	}
	ApiService.postForm(postCommentUrl, data, (result) => {
		console.log("Success:", result.data);
		/*if (result.result === 'ok')
			window.location.href = '/';
		else
			window.alert(result.message);*/
	},
	(error) => {
		console.error(error);
		window.alert(error);
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
	sendCommentButton.addEventListener('click', (e) => {
		e.preventDefault();
		sendComment(sendCommentInput.value);
	});
});