�Լ�д�Ĵ��벿��ű�

		#����GitHub�ϵĴ��뵽/tmpĿ¼��
		wget https://codeload.github.com/sampanhuang/diana/zip/publish -P /tmp
		#��ѹ�ļ�������
		unzip /tmp/publish -d /tmp/
		#����
		cp -rf /tmp/diana-publish/* /home/wwwroot/haihuamen.com/
		#�ļ���Ȩ
		chown -R www:www /home/wwwroot/haihuamen.com/
		chmod -R 440 /home/wwwroot/haihuamen.com/
		chmod -R 660 /home/wwwroot/haihuamen.com/temp
		#ɾ�������ļ�
		#rm -rf /tmp/publish /tmp/diana-publish


�������nginx����
urlrewrite��Ҫ����д

		location / {
		            index  index.php;
		            if (!-f $request_filename){
		                rewrite ^/(.+)$ /index.php?$1& last;
		            }
		        }

