require 'rubygems'
require 'digest/md5'
require 'rake/contrib/ftptools'

# This file stores $ftp_login and $ftp_password which are used for uploading.
if File.exist?('Rakefile.config')
  load 'Rakefile.config'
end

task :default => [:less]

desc 'Watch Less'
task :watch => :devless do
  system('when-changed _less/* -c rake devless')
end

desc 'Compile Less'
task :less do
  rm Dir.glob('css/*.css')
  mkdir_p 'css'
  system('lessc --yui-compress "_less/main.less" > "css/intermediate.css"')
  hash = Digest::MD5.file('css/intermediate.css').hexdigest()
  mv 'css/intermediate.css', 'css/'+hash+'.css'
  system('find . -name "*.html" -exec sed -i "s/<link rel=\"stylesheet\" href=\"css\/.*\.css\">/<link rel=\"stylesheet\" href=\"css\/'+hash+'\.css\">/g" {} \;')
end

desc 'Compile Less'
task :devless do
  rm Dir.glob('css/*.css')
  mkdir_p 'css'
  system('lessc "_less/main.less" > "css/intermediate.css"')
  system('find . -name "*.html" -exec sed -i "s/<link rel=\"stylesheet\" href=\"css\/.*\.css\">/<link rel=\"stylesheet\" href=\"css\/intermediate\.css\">/g" {} \;')
end

desc 'Running Jekyll with --auto option'
task :dev do
	system('jekyll server --watch')
end

task :beta => :less do
	system('jekyll --url http://beta.abenteuer-irland.de --base-url /')
	cd '_site' do
  	Rake::FtpUploader.connect('/html/beta-abenteuer-irland', $ftp_server, $ftp_login, $ftp_password) do |ftp|
    	ftp.verbose = true # gives you some output
    	ftp.upload_files("./**/*")
    	ftp.upload_files(".htaccess")
  	end
	end
end

task :upload => :less do
	system('jekyll --url http://abenteuer-irland.de --base-url /')
	cd '_site' do
  	Rake::FtpUploader.connect('/html/abenteuer-irland', $ftp_server, $ftp_login, $ftp_password) do |ftp|
    	ftp.verbose = true # gives you some output
    	ftp.upload_files("./**/*")
    	ftp.upload_files(".htaccess")
  	end
	end
end
