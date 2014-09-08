CallMeRpc
================================================================================
**A very simple http rpc server**

This is a HTTP RPC server. You define methods as single namespaced PHP files
containing a single function. You can then call these methods via normal GET
or POST requests (a standard HTML web form could easily be tied to a method).

From your function you can return what ever data you like. And CallMeRpc will
pass it on ontouched to the client. If however you return a serializable value
such as an array or object we will then convert it to JSON for you.

There is no set structure or scheme to the RPC methods,
this is entirely left up to the developers who create the methods.

A decriptor file is automatically created, similar to WSDL for SOAP services.
This file can be requested in JSON which can then in turn be parsed by the
remote end to create callable stub methods. However the decriptor file when
requested by a web browser is transformed into a human readable HTML5 page.
With testing functionality built in.

How do I use it?
--------------------------------------------------------------------------------
Well you can either download the zip file above or fork/clone the project on
GitHub. Once you have the files on a PHP server and the you should be pretty
much right to go.

TODO: Hopfully this will turn into a nice composer package one day...

TODO: More instructions here... I promise!

--------------------------------------------------------------------------------
Developed by Brad Jones - brad@bjc.id.au