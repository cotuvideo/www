'use strict';

const http = require('http');

const server = http.createServer((req, res) => {
	if(req.method === 'GET' && req.url === '/favicon.ico')
	{
		res.end();
		return;
	}
	console.info('['+new Date()+'] '+req.connection.remoteAddress+' '+req.method+' '+req.url);
	res.writeHead(200, {
		'Content-Type': 'text/html; charset=utf-8'
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

const port = 8000;
server.listen(port, () => {
	console.log('['+new Date()+'] Listening on '+port);
});
