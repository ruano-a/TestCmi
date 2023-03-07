class ViewService {
    formatDate(dateString) {
        const date = new Date(dateString);

        return date.toLocaleDateString("fr") + ' ' + date.toLocaleTimeString('fr');
    }
}

export default new ViewService();