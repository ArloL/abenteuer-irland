require 'rubygems'
require 'digest/md5'
require 'rake/contrib/ftptools'

task :default => [:lessc]

desc 'Watch Less'
task :watch do
  system('when-changed _less/* -c rake lessc')
end

desc 'Compile Less'
task :lessc do
  rm Dir.glob('css/*.css')
  mkdir_p 'css'
  system('lessc --yui-compress "_less/main.less" > "css/intermediate.css"')
  hash = Digest::MD5.file('css/intermediate.css').hexdigest()
  mv 'css/intermediate.css', 'css/'+hash+'.css'
  system('find . -name "*.html" -exec sed -i "s/<link rel=\"stylesheet\" href=\"css\/.*\.css\">/<link rel=\"stylesheet\" href=\"css\/'+hash+'\.css\">/g" {} \;')
end

desc 'Running Jekyll with --auto option'
task :dev do
	system('jekyll --auto')
end

task :beta do
	system('jekyll --base-url /')
	cd '_site' do
  	Rake::FtpUploader.connect('/html/beta-abenteuer-irland', 'abenteuer-irland.de', 'web329', 'HlHHvtWX') do |ftp|
    	ftp.verbose = true # gives you some output
    	ftp.upload_files("./**/*")
    	ftp.upload_files(".htaccess")
  	end
	end
end

task :upload do
	system('jekyll --base-url /')
	cd '_site' do
  	Rake::FtpUploader.connect('/html/abenteuer-irland', 'abenteuer-irland.de', 'web329', 'HlHHvtWX') do |ftp|
    	ftp.verbose = true # gives you some output
    	ftp.upload_files("./**/*")
    	ftp.upload_files(".htaccess")
  	end
	end
end
