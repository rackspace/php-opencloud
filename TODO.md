# Todo list for PHP OpenCloud

If you want to help out with new features for OpenCloud, feel free! You can get an overview of how to contribute by reading our [guide to contributing](https://github.com/rackspace/php-opencloud/blob/master/CONTRIBUTING.md). We've listed a few features below that would be nice to incorporate for future releases.

## Minor features

- Add keypair support for Server create
- Reformat smoketest.php for greater clarity, and make it more OO for code reuse.
- _Documentation_: update QuickReference so that it's clearer and more succinct; go over code samples to avoid
duplicate examples; make sure documentation is completely up-to-date and useful. Not just from a low-level 
code point of view, but also help to explain some of the bigger concepts taken for granted.

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

- _DataObject refactoring_: main for performance (issue #147), but also other issues which 
need to be addressed like content guessing (#143) and bulk delete (#139).

- _KeyStone identity_: we need to refactor how identity is handled in main OpenStack.php file.
I'd like to see a lot of the current client attributes abstracted out and made into their own 
object files.
