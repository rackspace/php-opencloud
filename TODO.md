# Todo list for PHP OpenCloud:

## Minor features

- Add keypair support for Server create

## Major features

- _Internationalization support_: This would involve creating a `lang` directory 
and sub-directories for each supported language. You'd have key/value pairs for
each key piece of text outputted in the SDK. Each piece of text would run through
a translate() method.

- _Unit test overhaul_: We need a major refactor of our test suite setup. At the
moment there's duplication and we have no systematic approach towards mock data.
Some people in the DRG are currently working on solutions, but perhaps we could
hack something quickly. From what it looks like, we'd use a HTTP proxy to capture
the requests and then produce recordings (like VCR in Ruby). We could write 
something in Golang. It's critical that we think about sensitive information.

