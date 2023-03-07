const API_BASE_URL = '/api/';

class ApiService {
    getApiBaseUrl() {
        // we could just export the constant, but hardly matters
        return API_BASE_URL;
    }

    get(url, callbackSuccess, callbackFail, callbackBoth) {
        const params = { credentials: 'include' };

        fetch(url, params).then((response) =>
            response
            .json()
            .then((data) => {
                console.log(data);
                if (callbackSuccess) callbackSuccess(data); // doesn't REALLY mean it's a success, just that the query went through and got an answer
                if (callbackBoth) callbackBoth(true, data);
            })
            .catch((error) => {
                console.log('erreur indeed');
                if (callbackFail) callbackFail(error);
                if (callbackBoth) callbackBoth(false);
            })
        );
    }

    post(url, data, callbackSuccess, callbackFail, callbackBoth) {
        fetch(url, {
            method: 'POST',
            headers: {
              "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
          credentials: "include",
        })
        .then((response) => response.json())
        .then((data) => {
            if (callbackSuccess) callbackSuccess(data); // doesn't REALLY mean it's a success, just that the query went through and got an answer
            if (callbackBoth) callbackBoth(true, data);
        })
        .catch((error) => {
            if (callbackFail) callbackFail(error);
            if (callbackBoth) callbackBoth(false);
        });
    }

    postForm(url, data, callbackSuccess, callbackFail, callbackBoth) {
        fetch(url, {
            method: 'POST',
            credentials: "include",
            body: data,
        })
        .then((response) => response.json())
        .then((data) => {
            if (callbackSuccess) callbackSuccess(data); // doesn't REALLY mean it's a success, just that the query went through and got an answer
            if (callbackBoth) callbackBoth(true, data);
        })
        .catch((error) => {
            if (callbackFail) callbackFail(error);
            if (callbackBoth) callbackBoth(false);
        });
    }
}

export default new ApiService();