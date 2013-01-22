require 'rubygems'
require 'rake/contrib/ftptools'

task :default => [:dev]

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
