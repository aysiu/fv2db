#!/bin/bash

#### Deferred enablement start
# Recovery path
recovery_path='/var/root/fderekey.plist'

# Get the last logged in user
last_user=$(defaults read /Library/Preferences/com.apple.loginwindow lastUserName)

sudo fdesetup enable -user "$last_user" -defer "$recovery_path" -forceatlogin 10
#### Deferred enablement end

#### Alternatively, using fde-rekey to generate a new key or to switch from institutional to personal
#https://github.com/square/fde-rekey

## Stuff to do later
# Website to curl
site='https://YOURDOMAIN.COM/PATH/TO/client/checkkey.php'

# Path to output file
output_plist='/var/root/fderekey.plist'

# Most important thing is that the output plist has to exist... if it doesn't, no point in running this script at all
if [ -f "$output_plist" ]; then

   # Get current Mac's serial number
   serial=$(ioreg -c IOPlatformExpertDevice -d 2 | awk -F\" '/IOPlatformSerialNumber/{print $(NF-1)}')

   # Get hostname
   comp_hostname=$(scutil --get HostName)

   # Get fv users
   fv_users=$(sudo fdesetup list)

   # Get recovery key
   recovery_key=$(defaults read "$output_plist" RecoveryKey)

   # Get the extra headers
   # Optional bit if you use Munki with basic auth
   #header=$(sudo defaults read /var/root/Library/Preferences/ManagedInstalls.plist AdditionalHttpHeaders | tr -d '()' | xargs )

   # Curl the website and send the appropriate info
   checkkey=$(curl -s \
      #--header "$header" \
      "$site" \
      -d recovery_key="$recovery_key" \
      -d serial="$serial" \
      -d comp_hostname="$comp_hostname" \
      -d fv_users="$fv_users")

   if [ "$checkkey" == "exists" ]; then
      # Already in the database
      exit 1
   else
      # Not in the database yet, so not "installed"
      exit 0
   fi

# If the output plist doesn't exist, we might as well consider this "installed"
else
   exit 1

# End checking the output plist originally existed
fi