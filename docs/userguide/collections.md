Working with Collections
========================

A *Collection* is a type of object that represents an ordered set of other objects.
You can think of this as a list of object, because that's how this is generally used.

For example, if you have a Compute object, the `ServerList()` method returns a
list of servers; that is, the result of the method is a `Collection` object containing
all of the servers in the Compute instance (it's possible to filter the list to 
retrieve only a subset, but we'll get to that in a moment).

`Collection` object have only three methods:

* `Next()` retrieves the next item in the set
* `First()` rewinds the set pointer to the beginning
* `Size()` returns the number of items in the set. 

## The Next() method

In most cases, you will use the `Next()` method in conjunction with a PHP `while` loop:

	// assume that $nova is a Compute object
	$servers = $nova->ServerList();	// retrieve a list of all the servers
	// now, process each server individually
	while ($myserver = $servers->Next()) {
		// do something with the Server object
		printf("Server name %s\n", $myserver->name);
	}

## The Reset() method 

This resets the pointer to the beginning of the list:

	$servers = $nova->ServerList();	// get all the servers
	while($serv = $servers->Next())	// print all the names
		print($serv->name."\n");
	$servers->Reset();
	while($serv = $servers->Next())	// reboot them all
		$serv->Reboot();

## The First() method

You only need to use the `First()` method to return to the beginning of the list and
return the first item:

	$servers = $nova->ServerList();	// get all the servers
	while($serv = $servers->Next())	// print all the names
		print($serv->name."\n");
	$servers->First()->Reboot();	// reboot the first server

## The Size() method

This simply returns the number of items in the list:

	$servers = $nova->ServerList();
	printf("You have %d server(s)\n", $servers->Size());

## What's next?

See [Exceptions and error handling](exceptions.md)