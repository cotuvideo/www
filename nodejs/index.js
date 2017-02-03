'use strict';

const http = require('http');

const server = http.createServer((req, res) => {
	console.info('['+new Date()+'] Requested by '+req.connection.remoteAddress);
	res.writeHead(200, {
		'Content-Type': 'text/html',
		'charset': 'utf-8'
	});
	res.write('<!DOCTYPE html><html lang="js"><body>\n');
	res.write(req.headers['user-agent']+'<br>\n');
	res.write('</body></html>\n');
	
	res.end();
}).on('error', (e) =>
{
	console.error('['+new Date()+'] Server Error', e);
}).on('clientError', (e) =>
{
	console.error('['+new Date()+'] Client Error', e);
});

const port = 80;
server.listen(port, () => {
	console.log('['+new Date()+'] Listening on '+port);
});
