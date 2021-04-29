const chokidar = require('chokidar');
const https = require('https');

const watcher = chokidar.watch('./storage/app/recordings', {
    awaitWriteFinish: true
});

const recordingAdded = (path) => {
    const data = JSON.stringify({ path });

    const options = {
        hostname: 'cmsrec.test',
        port: 443,
        path: '/fs/added',
        rejectUnauthorized: false,
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Content-Length': data.length
        }
    };

    const req = https.request(options, res => {
        console.log(`statusCode: ${res.statusCode}`);
        res.on('data', d => {
            console.log(`data: ${d}`);
        })
    });
    req.on('error', error => {
        console.error(error)
    });
    req.write(data);
    req.end()
};

const recordingDeleted = (path) => {
    console.log(`File ${path} has been deleted`)
};

watcher
    .on('add', path => recordingAdded(path))
    .on('unlink', path => recordingDeleted(path));
