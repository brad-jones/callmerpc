CallMeRpc - A very simple http rpc server, go on callme baby!
================================================================================
**To see this in action checkout: http://callmerpc.bjc.id.au/**

What is this?
--------------------------------------------------------------------------------
This is a HTTP RPC server. You define methods as single namespaced PHP files
containing a single function or class (some methods might make use of private
helper methods to achieve their task). You can then call these methods via
normal GET or POST requests (a standard HTML web form could easily be tied to
a method).

Each PHP function will return an array value which will then be
transformed into JSON or XML or some other serialisable format.
Configured as a server wide default and/or per request.

POST requests can also be made using JSON or XML instead of POST values.
In this case whatever format is used for the request will be used for the
response.

There is no set structure or scheme to the RPC methods, this is entirely left
up to the developers who create the methods.

A decriptor file is automatically created, similar to WSDL for SOAP services.
This file again can be requested in JSON or XML which can then in turn be
parsed by the remote end to create callable stub methods.
However the decriptor file when requested by a web browser is transformed
into a human readable HTML5 page. With testing functionality built in.

How do I use it?
--------------------------------------------------------------------------------
Well you can either download the zip file above or fork/clone the project on
GitHub. Once you have the files on a PHP server and the you should be pretty
much right to go.

```
TODO: More instructions here
```

Making Contributions
--------------------------------------------------------------------------------
This project is first and foremost a tool to help me create awsome websites.
Thus naturally I am going to tailor it for my use. I am just a really kind
person that has decided to share my code so I feel warm and fuzzy inside.
Thats what Open Source is all about, right :)

If you feel like you have some awsome new feature, or have found a bug I
overlooked I would be more than happy to hear from you. Simply create a new
issue on the github project, including a link to a patch file if you have some
changes already developed and I will consider your request.

  - If it does not impede on my use of the software.
  - If I feel it will benefit us and/or the greater community.
  - If you make it easy for me to implement - ie: provide a patch file.
  
Then the chances are I will include your code.

--------------------------------------------------------------------------------
Developed by Brad Jones - brad@bjc.id.au