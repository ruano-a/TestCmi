class ViewService {
    formatDate(dateString) {
        const date = new Date(dateString);

        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
    }
}

export default new ViewService();