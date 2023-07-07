const http = require('http');

let i = 0;
const server = http.createServer((req, res) => {
    console.log("%d - ", i++);
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('Hello, World!\n');
});

const port = 8999;

server.listen(port, () => {
    console.log(`Server running at http://localhost:${port}/`);
});
