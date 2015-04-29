Views
=====

Info
----

Views contain a combination of data that usually includes multiple,
different objects. The primary purpose of a view is to save API calls
and make data retrieval more efficient. Instead of doing multiple API
calls and then combining the result yourself, you can perform a single
API call against the view endpoint.

List all Views
--------------

\`\`\`php $views = $service->getViews();

foreach ($views as $view) { $entity = $view->getEntity();

::

    echo $view->getTimestamp();

} \`\`\`

Please consult the `iterator doc <docs/userguide/Iterators.md>`__ for
more information about iterators.
