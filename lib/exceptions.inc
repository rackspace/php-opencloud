<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud {

	/**
	 * General Exceptions
	 */
	class AttributeError extends \Exception {}
	class AuthenticationError extends \Exception {}
	class CollectionError extends \Exception {}
	class DocumentError extends \Exception {}
	class EndpointError extends \Exception {}
	class HttpError extends \Exception {}
	class HttpUnauthorizedError extends \Exception {}
	class HttpForbiddenError extends \Exception {}
	class HttpRetryError extends \Exception {}
	class HttpTimeoutError extends \Exception {}
	class HttpUrlError extends \Exception {}
	class HttpOverLimitError extends \Exception {}
	class JsonError extends \Exception {}
	class ServiceValueError extends \Exception {}
	class UnrecognizedServiceError extends \Exception {}
	class UrlError extends \Exception {}

	/**
	 * catch-all
	 */
	class UnknownError extends \Exception {}

}

namespace OpenCloud\Compute {

	class ImageError extends \Exception {}
	class InvalidArgumentError extends \Exception {}
	class FlavorError extends \Exception {}
	class EmptyResponseError extends \Exception {}
	class InvalidRequestError extends \Exception {}
	class InvalidParameterError extends \Exception {}
	class InvalidIpTypeError extends \Exception {}
	class MetadataError extends \Exception {}
	class MetadataKeyError extends \Exception {}
	class MetadataJsonError extends \Exception {}
	class MetadataCreateError extends \Exception {}
	class MetadataUpdateError extends \Exception {}
	class MetadataDeleteError extends \Exception {}
	class ServerCreateError extends \Exception {}
	class ServerUpdateError extends \Exception {}
	class ServerDeleteError extends \Exception {}
	class ServerActionError extends \Exception {}
	class ServerIdRequiredError extends \Exception {}
	class ServerIpsError extends \Exception {}
	class ServerUrlError extends \Exception {}
	class ServerJsonError extends \Exception {}

}

namespace OpenCloud\ObjectStore {
    class CdnError extends \Exception {}
    class CdnNotAvailableError extends \Exception {}
    class CdnHttpError extends \Exception {}
    class CdnTtlError extends \Exception {}
    class CreateUpdateError extends \Exception {}
    class ContainerCreateError extends \Exception {}
    class ContainerDeleteError extends \Exception {}
    class ContainerNameError extends \Exception {}
    class ContainerNotFoundError extends \Exception {}
    class ContainerNotEmptyError extends \Exception {}
    class DeleteError extends \Exception {}
    class IOError extends \Exception {}
    class MetadataPrefixError extends \Exception {}
    class MisMatchedChecksumError extends \Exception {}
    class NoNameError extends \Exception {}
    class NoContentTypeError extends \Exception {}
    class ObjectError extends \Exception {}     // SyntaxException
    class ObjectCopyError extends \Exception {}
    class ObjFetchError extends \Exception {}
    class UnknownParameterError extends \Exception {}
}

namespace OpenCloud\DbService {
	class DatabaseCreateError extends \Exception {}
	class DatabaseDeleteError extends \Exception {}
	class DatabaseListError extends \Exception {}
	class DatabaseUpdateError extends \Exception {}
    class DatabaseNameError extends \Exception {}
	class InstanceCreateError extends \Exception {}
	class InstanceDeleteError extends \Exception {}
	class InstanceUpdateError extends \Exception {}
    class InstanceError extends \Exception {}
    class InstanceFlavorError extends \Exception {}
	class InvalidIdTypeError extends \Exception {}
	class UserCreateError extends \Exception {}
	class UserDeleteError extends \Exception {}
	class UserListError extends \Exception {}
	class UserUpdateError extends \Exception {}
	class UserNameError extends \Exception {}
}
