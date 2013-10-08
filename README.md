CallMeRpc - A very simple http rpc server, go on callme baby!
================================================================================
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

How to Install
--------------------------------------------------------------------------------
Installation via composer is easy:

	composer require gears/callmerpc:x

How do I use it?
--------------------------------------------------------------------------------

```
Example will go here
```
