# UnlockED 0.1.0-alpha - 01 Nov 2021 {#mainpage}

## License

Copyright (C) 2021 UnlockedLabs. <http://unlockedlabs.org/>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

UnlockED v.1.0 alpha is currently released under Apache-2.0. This is subject to change in the first official release of UnlockED v.1.0. Under the terms of the Apache-2.0 you are free to use and modify UnlockED as you like, so long as you leave the above copyright in place and include this readme with the source code or any derivative work thereof.

---

## Introduction
UnlockED is a Learning Management System that was created to address the educational needs of criminal justice involved individuals. It is primarily designed, implemented, and maintained by such individuals.

## Requirements
This software requires:

*	Apache 2.0+
*	PHP 7.0+
*	MySQL 8.0+

## Xampp Installation:
One option, is to install Xampp, which includes all of the above requirements. This would typically by done on a clean
system, because otherwise you are likely to already have something like mysql or mariadb installed and they can conflict

    wget https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.4/xampp-linux-x64-8.2.4-0-installer.run -o xampp.run

    sudo chmod +x xampp.run

This will bring you through the GUI installation process. You will typically install in /opt/lampp and then you should
clone the UnlockEd git repository to /opt/lampp/htdocs (which will require sudo privilages to write to that directory)

    sudo git clone https://github.com/UnlockedLabs/UnlockEd-Open.git /opt/lampp/htdocs

Afterwards, you can make sure you are in the lampp directory:
 
    cd /opt/lampp
    sudo ./xampp start

Then in your browser, go to `http://localhost/phpmyadmin`
if there is an error regarding mysql, it is very likely a socket error... something like 
  
    cannot connect to mysql server through socket 'var run mysqld mysqld.sock'

to fix this, we need to either find, or create our /etc/my.cnf file. If it already exists, we need to add the following.
First, in a terminal run `sudo find / -type s | grep mysql.sock`
to make sure that the mysql instance running with xampp is being used. If it is, the output should be 
    
    /opt/lampp/var/mysql/mysql.sock

and we need to add that to our `/etc/my.cnf` file, with the port 3306. if no /etc/my.cnf file exists, (and there is no mysql directory) we need to create one. 

    sudo curl https://gist.github.com/PThorpe92/885d31810852d3d0360a4982bd96febf/raw/bbeaaddb751eb05073f38ab443a21ffc3c551a79/my.cnf -o /etc/my.cnf

Now after `sudo ./opt/lampp/xampp restart` you should be able to log into mysql from the command line or from `http://localhost/phpmyadmin`
and create the tables in step 4 below, and pick up from there.

## Usage
Deployment of UnlockED is intended to be very straightforward. It can be deployed by:

1. Cloning this project repo to the root of their apache web server
2. Set permissions on the cloned directory to allow apache to write to the directory (UNSAFE QUICK ANSWER: chmod 777 -R UnlockEd-Open)
3. Setting up a virtual host that points to the root directory of this project
4. Create the following MySQL databases: 'learning_center_api_db' and 'learning_center_api_analytics'
5. Adjusting the database connection settings in the /config/database.php and /analytics/config/database.php files to match those of your MySql server:

        // specify your own database credentials
        
        private $host = "localhost";
        private $db_name = "learning_center_api_db";
        private $username = "root";
        private $password = "yourpassword";
        public $conn;

6. Set your site_url in the create tables script (NOTE: You can either do this here, or complete the table population and
manually modify the setting in the site_settings table):
- On line 165 of config/populate-tables.php change the site_url value from :

>'bc1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'site_url', 'http://ec2-50-16-145-134.compute-1.amazonaws.com/', '1' 
    
to

>'bc1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'site_url', '[your site's base url]', '1',

    if you are running a dev server locally, you need to put `http://localhost/UnlockEd-Open/` as your site_url

[replace the bracketed text with your base url to the UnlockEd instance]

7. Installing the MySql tables by running the table creation/population scripts (i.e. open the following in a browser in order):
>Make sure the user you configured in config/database.php is using native authentication mode


* /config/create-tables.php
* /config/populate-tables.php
* /analytics/config/create-tables.php
* /analytics/config/populate-tables.php
8. Open a browser to the root of the domain.
9. Login using the credentials:
	* User: superadmin
	* Password: pwd

NOTE: If you did not modify the populate-tables.php script before you ran the scripts in step 7, then you must modify the site_setting in your mysql database:

- connect with your mysql client to the learning_center_api_db database
- run the command 'UPDATE site_settings SET value=[your sites base url] WHERE settings=site_url;'  

## Development Team

*	Haley Shoaf (haley@unlockedlabs.org) - UnlockedLabs CEO/Project Owner
*	Jessica Hicklin (jessica@unlockedlabs.org) - UnlockedLabs CTO, Project Manager, Developer
*	Peter Tosto (peter@unlockedlabs.org) - %Product Design Consultant
*	Christopher Santillan (chris@unlockedlabs.org) - Developer

## Versioning Policy
Version numbers are of the form *major*.*minor*.*patch*.

## Bugs
Who knows, probably a lot... this is an alpha version.


## Development and Testing
Currently we are not accepting pull requests for fixing bugs. Once the official version 1.0.0 release is made, we will begin accepting both pull requests for bug fixes and suggestions for features.

## Donations
If you wish to make a donation that will help us devote more time to
UnlockED, please visit [unlockedlabs.org] and contact us through our contact form..

 [unlockedlabs.org]: http://unlockedlabs.org
 

## Version History
UnlockED 0.1.0 - Alpha (01 Nov 2021)

* Released first version with a built-in Adult Basic Education curriculum

UnlockED 0.0.1 - Alpha (14 Apr 2021)

*	Established the project as an open source project.

UnlockED 0.0.0 - pre-Alpha (03 Feb 2017)

*	UnlockED was created and deployed in a correctional context as the Missouri Offender Recovery Environment.
*	More then 1,000 users interacted with the system before the official alpha release.

Copyright and License
---------------------

UnlockED -alpha is currently being released under the Apache-2.0. This is subject to change when the first official public version of UnlockED is made available.

SPDX short identifier: Apache-2.0

Apache License
Version 2.0, January 2004
http://www.apache.org/licenses/

TERMS AND CONDITIONS FOR USE, REPRODUCTION, AND DISTRIBUTION
1. Definitions.
“License” shall mean the terms and conditions for use, reproduction, and distribution as defined by Sections 1 through 9 of this document.

“Licensor” shall mean the copyright owner or entity authorized by the copyright owner that is granting the License.

“Legal Entity” shall mean the union of the acting entity and all other entities that control, are controlled by, or are under common control with that entity. For the purposes of this definition, “control” means (i) the power, direct or indirect, to cause the direction or management of such entity, whether by contract or otherwise, or (ii) ownership of fifty percent (50%) or more of the outstanding shares, or (iii) beneficial ownership of such entity.

“You” (or “Your”) shall mean an individual or Legal Entity exercising permissions granted by this License.

“Source” form shall mean the preferred form for making modifications, including but not limited to software source code, documentation source, and configuration files.

“Object” form shall mean any form resulting from mechanical transformation or translation of a Source form, including but not limited to compiled object code, generated documentation, and conversions to other media types.

“Work” shall mean the work of authorship, whether in Source or Object form, made available under the License, as indicated by a copyright notice that is included in or attached to the work (an example is provided in the Appendix below).

“Derivative Works” shall mean any work, whether in Source or Object form, that is based on (or derived from) the Work and for which the editorial revisions, annotations, elaborations, or other modifications represent, as a whole, an original work of authorship. For the purposes of this License, Derivative Works shall not include works that remain separable from, or merely link (or bind by name) to the interfaces of, the Work and Derivative Works thereof.

“Contribution” shall mean any work of authorship, including the original version of the Work and any modifications or additions to that Work or Derivative Works thereof, that is intentionally submitted to Licensor for inclusion in the Work by the copyright owner or by an individual or Legal Entity authorized to submit on behalf of the copyright owner. For the purposes of this definition, “submitted” means any form of electronic, verbal, or written communication sent to the Licensor or its representatives, including but not limited to communication on electronic mailing lists, source code control systems, and issue tracking systems that are managed by, or on behalf of, the Licensor for the purpose of discussing and improving the Work, but excluding communication that is conspicuously marked or otherwise designated in writing by the copyright owner as “Not a Contribution.”

“Contributor” shall mean Licensor and any individual or Legal Entity on behalf of whom a Contribution has been received by Licensor and subsequently incorporated within the Work.

2. Grant of Copyright License.
Subject to the terms and conditions of this License, each Contributor hereby grants to You a perpetual, worldwide, non-exclusive, no-charge, royalty-free, irrevocable copyright license to reproduce, prepare Derivative Works of, publicly display, publicly perform, sublicense, and distribute the Work and such Derivative Works in Source or Object form.

3. Grant of Patent License.
Subject to the terms and conditions of this License, each Contributor hereby grants to You a perpetual, worldwide, non-exclusive, no-charge, royalty-free, irrevocable (except as stated in this section) patent license to make, have made, use, offer to sell, sell, import, and otherwise transfer the Work, where such license applies only to those patent claims licensable by such Contributor that are necessarily infringed by their Contribution(s) alone or by combination of their Contribution(s) with the Work to which such Contribution(s) was submitted. If You institute patent litigation against any entity (including a cross-claim or counterclaim in a lawsuit) alleging that the Work or a Contribution incorporated within the Work constitutes direct or contributory patent infringement, then any patent licenses granted to You under this License for that Work shall terminate as of the date such litigation is filed.

4. Redistribution.
You may reproduce and distribute copies of the Work or Derivative Works thereof in any medium, with or without modifications, and in Source or Object form, provided that You meet the following conditions:

You must give any other recipients of the Work or Derivative Works a copy of this License; and
You must cause any modified files to carry prominent notices stating that You changed the files; and
You must retain, in the Source form of any Derivative Works that You distribute, all copyright, patent, trademark, and attribution notices from the Source form of the Work, excluding those notices that do not pertain to any part of the Derivative Works; and
If the Work includes a “NOTICE” text file as part of its distribution, then any Derivative Works that You distribute must include a readable copy of the attribution notices contained within such NOTICE file, excluding those notices that do not pertain to any part of the Derivative Works, in at least one of the following places: within a NOTICE text file distributed as part of the Derivative Works; within the Source form or documentation, if provided along with the Derivative Works; or, within a display generated by the Derivative Works, if and wherever such third-party notices normally appear. The contents of the NOTICE file are for informational purposes only and do not modify the License. You may add Your own attribution notices within Derivative Works that You distribute, alongside or as an addendum to the NOTICE text from the Work, provided that such additional attribution notices cannot be construed as modifying the License.
You may add Your own copyright statement to Your modifications and may provide additional or different license terms and conditions for use, reproduction, or distribution of Your modifications, or for any such Derivative Works as a whole, provided Your use, reproduction, and distribution of the Work otherwise complies with the conditions stated in this License.

5. Submission of Contributions.
Unless You explicitly state otherwise, any Contribution intentionally submitted for inclusion in the Work by You to the Licensor shall be under the terms and conditions of this License, without any additional terms or conditions. Notwithstanding the above, nothing herein shall supersede or modify the terms of any separate license agreement you may have executed with Licensor regarding such Contributions.

6. Trademarks.
This License does not grant permission to use the trade names, trademarks, service marks, or product names of the Licensor, except as required for reasonable and customary use in describing the origin of the Work and reproducing the content of the NOTICE file.

7. Disclaimer of Warranty.
Unless required by applicable law or agreed to in writing, Licensor provides the Work (and each Contributor provides its Contributions) on an “AS IS” BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied, including, without limitation, any warranties or conditions of TITLE, NON-INFRINGEMENT, MERCHANTABILITY, or FITNESS FOR A PARTICULAR PURPOSE. You are solely responsible for determining the appropriateness of using or redistributing the Work and assume any risks associated with Your exercise of permissions under this License.

8. Limitation of Liability.
In no event and under no legal theory, whether in tort (including negligence), contract, or otherwise, unless required by applicable law (such as deliberate and grossly negligent acts) or agreed to in writing, shall any Contributor be liable to You for damages, including any direct, indirect, special, incidental, or consequential damages of any character arising as a result of this License or out of the use or inability to use the Work (including but not limited to damages for loss of goodwill, work stoppage, computer failure or malfunction, or any and all other commercial damages or losses), even if such Contributor has been advised of the possibility of such damages.

9. Accepting Warranty or Additional Liability.
While redistributing the Work or Derivative Works thereof, You may choose to offer, and charge a fee for, acceptance of support, warranty, indemnity, or other liability obligations and/or rights consistent with this License. However, in accepting such obligations, You may act only on Your own behalf and on Your sole responsibility, not on behalf of any other Contributor, and only if You agree to indemnify, defend, and hold each Contributor harmless for any liability incurred by, or claims asserted against, such Contributor by reason of your accepting any such warranty or additional liability.

END OF TERMS AND CONDITIONS

