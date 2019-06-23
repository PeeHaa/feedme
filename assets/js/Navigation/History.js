export default class History {
    pushUrl(url) {
        console.log('Pushing URL: ' + url);
        history.pushState({page: 1}, null, url);
    }
}
