# fv2db
FileVault 2 Database

## What is fv2db?
This is a fun little project I created to store FileVault2 personal recovery keys in a MySQL database. This project isn't primarily for general consumption. It's really a "This works for me, and if it works for you, too, great!" kind of project. That said, I did structure it in such a way that the settings all live in one file (config.php), so you can change whatever you need to do suit your environment should you find this project useful for your organization.

## How does fv2db work?
I tried to make it as barebones and transparent as possible. In a nutshell, fv2db has a (bash) script (which you can run however you want; I've practiced running it with a Munki nopkg) that finds the recovery key output plist, sends it to the server, the server sees if that exact combination of serial number and recovery key exist already (in the MySQL database), and then the server adds any new combinations. Admins can log into a web interface (PHP via Google+ authentication) and see what recovery keys are in there.

## How do you set up fv2db?

### Web Server
I can't go into all the details here, but a web server of some kind that can server up PHP would be helpful--Apache, for example. You want to enable SSL with a proper (e.g., using Let's Encrypt) certificate or a self-signed one. And you may also want to enable basic authentication with a .htpasswd file. You can see an example of that in [Using https / self-signed certificates and basic authentication with Munki](https://technology.siprep.org/using-https-self-signed-certificates-and-basic-authentication-with-munki/). The .htpasswd file should go inside the *client* folder alongside the *checkkey.php* file.

### Web Files
The files in *files* should go on your web server. Make a copy of *config_sample.php* and rename it *config.php*. Then fill in all the appropriate fields (credentials for your MySQL database, Google developer information, etc.).

### MySQL Database
The database has only two tables. One is a list of admin users. The other stores the recovery keys, serial numbers, and other information for the client machines. You can run the *setup.sql* commands to create the database tables. You may also want to insert a first user, who can then log in and add other admin users: `INSERT INTO users (email) VALUES ('firstuseremail@yourdomain.com')`

### sample_script
The sample script actually could be several scripts, but I jammed all the commands together so you could just see the basic logic. It could use a deferred enablement of FileVault2 or it could use [fde-rekey](https://github.com/square/fde-rekey) to get an output .plist to send to the server.
