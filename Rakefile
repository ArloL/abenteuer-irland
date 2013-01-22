require 'rubygems'
require 'rake/contrib/ftptools'

task :default => [:dev]

desc 'Running Jekyll with --server --auto option'
task :dev do
  system('jekyll --server --auto')
end

task :upload do
	system('jekyll')
	cd '_site' do
  	Rake::FtpUploader.connect('/html/abenteuer-irland', 'abenteuer-irland.de', 'web329', 'HlHHvtWX') do |ftp|
    	ftp.verbose = true # gives you some output
    	ftp.upload_files("./**/*")
      ftp.upload_files(".htaccess")
  	end
	end
end
