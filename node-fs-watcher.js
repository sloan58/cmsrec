const chokidar = require('chokidar');
const https = require('https');
const axios = require('axios');

const watcher = chokidar.watch('./storage/app/recordings', {
    awaitWriteFinish: true
});

const cmsRecApi = axios.create({
    baseURL: 'https://cmsrec.test',
    httpsAgent: new https.Agent({
        rejectUnauthorized: false
    })
});

const recordingAdded = (path) => {
    const data = JSON.stringify({ path });
    cmsRecApi.post('/fs/added', data)
        .then(function (response) {
            console.log(`${response.status}: ${response.data}`);
        })
        .catch(function (error) {
            console.log(error);
        });
};

const recordingDeleted = (path) => {
    console.log(`File ${path} has been deleted`)
};

watcher
    .on('add', path => recordingAdded(path))
    .on('unlink', path => recordingDeleted(path));
