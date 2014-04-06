WORK IN PROGRESS !!!

If you want to checkout Catroweb, have a look at this repository: https://github.com/Catrobat/Catroweb


HowTo for installing Sonata:
	- $ php composer.phar install
	- $ php app/console doctrine:schema:update --force
	- $ php app/console init:acl
	- $ php app/console fos:user:create --super-admin
	- $ php app/console sonata:admin:setup-acl
	- $ php app/console sonata:admin:generate-object-acl
	- copy the line 'security.acl.permission.map.class: Sonata\AdminBundle\Security\Acl\Permission\AdminPermissionMap' from
	  parameters.yml.dist into parameters.yml
	- now go to catroid.local/app_dev.php/admin and enjoy ;)

HowTo for installing CasperJs and PhantomJs:\n
	- $ go to vendor/ and clone following repo: git clone git://github.com/n1k0/casperjs.git
	- $ cd casperjs
	- $ ln -sf `pwd`/bin/casperjs /usr/local/bin/casperjs
	- download and extract to vendor/pantomjs: https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.7-linux-x86_64.tar.bz2
	- $ ln -sf `pwd`/bin/phantom /usr/local/bin/phantomjs
	- now you can start a test with following command: casperjs test path-to-testfile/testfile
