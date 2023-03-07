import ApiService from './common/ApiService.js';
import './styles/article.css';

const articleBaseUrl = ApiService.getApiBaseUrl() + 'article/';
const commentsBaseUrl = ApiService.getApiBaseUrl() + 'comment/';

function createCommentBlock(commentData) {
	const container = document.createElement("div");
	container.className = 'recent-comment';
	container.innerHTML = '<div class="recent-comment-article">For the article <a href="'+ articleBaseUrl + commentData.article.id + '">"' + commentData.article.title + '"</a></div>'
							+ '<div class="recent-comment-content">' + commentData.text + '</div>'
							+ '<div class="recent-comment-date">' + commentData.creationDate + '</div>';
	return container;
}

document.addEventListener('DOMContentLoaded', function() {
	const id = '';
	ApiService.get(articleBaseUrl + id, (result) => {
		const titleContainer = document.getElementById('article-title');
		const contentContainer = document.getElementById('article-content');
		const dateContainer = document.getElementById('article-date');
		const authorContainer = document.getElementById('article-author');
		const commentsButton = document.getElementById('load-comments');
		
		titleContainer.innerHTML = result.title;
		contentContainer.innerHTML = result.content;
		dateContainer.innerHTML = result.creationDate;
		authorContainer.innerHTML = result.createdByUser;
	},
	(error) => {
		console.error(error);
		window.alert(error);
	});
});