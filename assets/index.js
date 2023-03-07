import ApiService from './common/ApiService.js';
import './styles/index.css';

const articleBaseUrl = '/article/';

function createCommentBlock(commentData) {
	const container = document.createElement("div");
	container.className = 'recent-comment';
	container.innerHTML = '<div class="recent-comment-article">For the article <a href="'+ articleBaseUrl + commentData.article.id + '">"' + commentData.article.title + '"</a></div>'
							+ '<div class="recent-comment-content">' + commentData.text + '</div>'
							+ '<div class="recent-comment-date">' + commentData.creationDate + '</div>';
	return container;
}

document.addEventListener('DOMContentLoaded', function() {
	ApiService.get(ApiService.getApiBaseUrl() + 'comment/recent', (result) => {
		const commentContainer = document.getElementById('recent-comments')
		result.forEach(commentData => {
			commentContainer.appendChild(createCommentBlock(commentData));
		});
	},
	(error) => {
		console.error(error);
		window.alert(error);
	});
});