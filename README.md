# POC DOL MOB

This module is intended to be a "POC" (Proof of Concept") or Quistart for developpers (only) to build a responsive webapp on top of a Dolibarr install

** It's not usable in a production environment, it's not secured, keep in mind it's a prototype **

I'ts mainly intended to be used with a smartphone or tablett (even if it's also working on computer).

It has been tested with Firefow Mobile and Chrome Mobile

## Installation
Installation is made like a classic Dolibarr module, copy all the axs2all folder in htdocs/custom and activate the module.

I strongly advise to add another subdomain in your install like mobdol.mydomain.com that points directly to dol_instal_folder/htdocs/custom/axs4all/www

And you've to activate and force https if you want to use the QR/Barcode scanner

## short description

I tried to respect "KISS", Keep It Stupid Simple ... as possible

It integrates 
- bootstrap 5.2.1
- jquery 3.6.1 (I continue to prefer - maybe because I'm too lazy - jquery syntax to classic javascript query syntax)
- authentification thru dolibarr auth system
- dynamic lists without hugly JS, using <datalist></datalist> wich is filled by ajax with dolibarr values : see www/assets/datalist_utils.js, which call www/includes/ajaxDataList.php. 
For the moment, it can retrieve only project, product and user list
*NB* : the datalist doesn't run ok (not implemented) on Firefox Mobile
- a fantastic [js plugin to scan QR/barcodes](https://blog.minhazav.dev/research/html5-qrcode.html), thanks a lot to [Minhaz](https://blog.minhazav.dev/), Senior Software Engineer at Google
- a page to directly take photo from the smartphone, or upload existing photos on the phone to dolibarr (in the demo, photos are uploaded in a project document section)
Thanks a lot to [Bensonruan](https://github.com/bensonruan/webcam-easy)
- a page to draw (for example to let sign a doc by a customer) and send to dolibarr
Thanks a lot to cnbilgin https://github.com/cnbilgin/jquery-drawpad, and https://github.com/cnbilgin/jquery-drawpad

As this plugin is free, there is no support. But if you want me to developp a custom thing using it, I'm available ;-)

## Démo
- Classic Dolibarr https://dolibarr-cur.dlcube.com
- Responsive webapp : https://dolmob.dlcube.com

Auth :
- user : demo
- passwd : demodemodemo
