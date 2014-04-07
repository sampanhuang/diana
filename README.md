自己写的代码部署脚本

		#下载GitHub上的代码到/tmp目录下
		wget https://codeload.github.com/sampanhuang/diana/zip/publish -P /tmp
		#解压文件到本地
		unzip /tmp/publish -d /tmp/
		#复制
		cp -rf /tmp/diana-publish/* /home/wwwroot/haihuamen.com/
		#文件授权
		chown -R www:www /home/wwwroot/haihuamen.com/
		chmod -R 440 /home/wwwroot/haihuamen.com/
		chmod -R 660 /home/wwwroot/haihuamen.com/temp
		#删除下载文件
		#rm -rf /tmp/publish /tmp/diana-publish


如果是在nginx下面
urlrewrite需要这样写

		location / {
		            index  index.php;
		            if (!-f $request_filename){
		                rewrite ^/(.+)$ /index.php?$1& last;
		            }
		        }

