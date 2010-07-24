<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette\Loaders
 */

namespace Nette\Loaders;

use Nette;



/**
 * Nette auto loader is responsible for loading Nette classes and interfaces.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Loaders
 */
class NetteLoader extends AutoLoader
{
	/** @var NetteLoader */
	private static $instance;

	/** @var array */
	public $list = array(
		'argumentoutofrangeexception' => '/Utils/exceptions.php',
		'datetime53' => '/Utils/DateTime53.php',
		'deprecatedexception' => '/Utils/exceptions.php',
		'directorynotfoundexception' => '/Utils/exceptions.php',
		'fatalerrorexception' => '/Utils/exceptions.php',
		'filenotfoundexception' => '/Utils/exceptions.php',
		'invalidstateexception' => '/Utils/exceptions.php',
		'ioexception' => '/Utils/exceptions.php',
		'memberaccessexception' => '/Utils/exceptions.php',
		'nette\ambiguousserviceexception' => '/Environment/ServiceLocator.php',
		'nette\application\abortexception' => '/Application/Exceptions/AbortException.php',
		'nette\application\appform' => '/Application/AppForm.php',
		'nette\application\application' => '/Application/Application.php',
		'nette\application\applicationexception' => '/Application/Exceptions/ApplicationException.php',
		'nette\application\badrequestexception' => '/Application/Exceptions/BadRequestException.php',
		'nette\application\badsignalexception' => '/Application/Exceptions/BadSignalException.php',
		'nette\application\clirouter' => '/Application/Routers/CliRouter.php',
		'nette\application\control' => '/Application/Control.php',
		'nette\application\downloadresponse' => '/Application/Responses/DownloadResponse.php',
		'nette\application\forbiddenrequestexception' => '/Application/Exceptions/ForbiddenRequestException.php',
		'nette\application\forwardingresponse' => '/Application/Responses/ForwardingResponse.php',
		'nette\application\invalidlinkexception' => '/Application/Exceptions/InvalidLinkException.php',
		'nette\application\invalidpresenterexception' => '/Application/Exceptions/InvalidPresenterException.php',
		'nette\application\ipartiallyrenderable' => '/Application/IRenderable.php',
		'nette\application\ipresenter' => '/Application/IPresenter.php',
		'nette\application\ipresenterloader' => '/Application/IPresenterLoader.php',
		'nette\application\ipresenterresponse' => '/Application/IPresenterResponse.php',
		'nette\application\irenderable' => '/Application/IRenderable.php',
		'nette\application\irouter' => '/Application/IRouter.php',
		'nette\application\isignalreceiver' => '/Application/ISignalReceiver.php',
		'nette\application\istatepersistent' => '/Application/IStatePersistent.php',
		'nette\application\jsonresponse' => '/Application/Responses/JsonResponse.php',
		'nette\application\link' => '/Application/Link.php',
		'nette\application\multirouter' => '/Application/Routers/MultiRouter.php',
		'nette\application\presenter' => '/Application/Presenter.php',
		'nette\application\presentercomponent' => '/Application/PresenterComponent.php',
		'nette\application\presentercomponentreflection' => '/Application/PresenterComponentReflection.php',
		'nette\application\presenterloader' => '/Application/PresenterLoader.php',
		'nette\application\presenterrequest' => '/Application/PresenterRequest.php',
		'nette\application\redirectingresponse' => '/Application/Responses/RedirectingResponse.php',
		'nette\application\renderresponse' => '/Application/Responses/RenderResponse.php',
		'nette\application\route' => '/Application/Routers/Route.php',
		'nette\application\routingdebugger' => '/Application/RoutingDebugger.php',
		'nette\application\simplerouter' => '/Application/Routers/SimpleRouter.php',
		'nette\arraylist' => '/Utils/ArrayList.php',
		'nette\arraytools' => '/Utils/ArrayTools.php',
		'nette\caching\apcstorage' => '/Caching/ApcStorage.php',
		'nette\caching\cache' => '/Caching/Cache.php',
		'nette\caching\dummystorage' => '/Caching/DummyStorage.php',
		'nette\caching\filestorage' => '/Caching/FileStorage.php',
		'nette\caching\icachejournal' => '/Caching/ICacheJournal.php',
		'nette\caching\icachestorage' => '/Caching/ICacheStorage.php',
		'nette\caching\memcachedstorage' => '/Caching/MemcachedStorage.php',
		'nette\caching\sqlitejournal' => '/Caching/SqliteJournal.php',
		'nette\caching\sqlitemimic' => '/Caching/SqliteJournal.php',
		'nette\callback' => '/Utils/Callback.php',
		'nette\component' => '/ComponentModel/Component.php',
		'nette\componentcontainer' => '/ComponentModel/ComponentContainer.php',
		'nette\config\config' => '/Config/Config.php',
		'nette\config\configadapterini' => '/Config/ConfigAdapterIni.php',
		'nette\config\iconfigadapter' => '/Config/IConfigAdapter.php',
		'nette\configurator' => '/Environment/Configurator.php',
		'nette\debug' => '/Debug/Debug.php',
		'nette\debugpanel' => '/Debug/DebugPanel.php',
		'nette\environment' => '/Environment/Environment.php',
		'nette\forms\button' => '/Forms/Controls/Button.php',
		'nette\forms\checkbox' => '/Forms/Controls/Checkbox.php',
		'nette\forms\conventionalrenderer' => '/Forms/Renderers/ConventionalRenderer.php',
		'nette\forms\fileupload' => '/Forms/Controls/FileUpload.php',
		'nette\forms\form' => '/Forms/Form.php',
		'nette\forms\formcontainer' => '/Forms/FormContainer.php',
		'nette\forms\formcontrol' => '/Forms/Controls/FormControl.php',
		'nette\forms\formgroup' => '/Forms/FormGroup.php',
		'nette\forms\hiddenfield' => '/Forms/Controls/HiddenField.php',
		'nette\forms\iformcontrol' => '/Forms/IFormControl.php',
		'nette\forms\iformrenderer' => '/Forms/IFormRenderer.php',
		'nette\forms\imagebutton' => '/Forms/Controls/ImageButton.php',
		'nette\forms\inamingcontainer' => '/Forms/INamingContainer.php',
		'nette\forms\instantclientscript' => '/Forms/Renderers/InstantClientScript.php',
		'nette\forms\isubmittercontrol' => '/Forms/ISubmitterControl.php',
		'nette\forms\multiselectbox' => '/Forms/Controls/MultiSelectBox.php',
		'nette\forms\radiolist' => '/Forms/Controls/RadioList.php',
		'nette\forms\rule' => '/Forms/Rule.php',
		'nette\forms\rules' => '/Forms/Rules.php',
		'nette\forms\selectbox' => '/Forms/Controls/SelectBox.php',
		'nette\forms\submitbutton' => '/Forms/Controls/SubmitButton.php',
		'nette\forms\textarea' => '/Forms/Controls/TextArea.php',
		'nette\forms\textbase' => '/Forms/Controls/TextBase.php',
		'nette\forms\textinput' => '/Forms/Controls/TextInput.php',
		'nette\framework' => '/Utils/Framework.php',
		'nette\freezableobject' => '/Utils/FreezableObject.php',
		'nette\genericrecursiveiterator' => '/Utils/Iterators/GenericRecursiveIterator.php',
		'nette\icomponent' => '/ComponentModel/IComponent.php',
		'nette\icomponentcontainer' => '/ComponentModel/IComponentContainer.php',
		'nette\idebugpanel' => '/Debug/IDebugPanel.php',
		'nette\image' => '/Utils/Image.php',
		'nette\imagemagick' => '/Utils/ImageMagick.php',
		'nette\instancefilteriterator' => '/Utils/Iterators/InstanceFilterIterator.php',
		'nette\iservicelocator' => '/Environment/IServiceLocator.php',
		'nette\itranslator' => '/Utils/ITranslator.php',
		'nette\json' => '/Utils/Json.php',
		'nette\jsonexception' => '/Utils/Json.php',
		'nette\loaders\autoloader' => '/Loaders/AutoLoader.php',
		'nette\loaders\limitedscope' => '/Loaders/LimitedScope.php',
		'nette\loaders\netteloader' => '/Loaders/NetteLoader.php',
		'nette\loaders\robotloader' => '/Loaders/RobotLoader.php',
		'nette\mail\imailer' => '/Mail/IMailer.php',
		'nette\mail\mail' => '/Mail/Mail.php',
		'nette\mail\mailmimepart' => '/Mail/MailMimePart.php',
		'nette\mail\sendmailmailer' => '/Mail/SendmailMailer.php',
		'nette\neonparser' => '/Utils/NeonParser.php',
		'nette\object' => '/Utils/Object.php',
		'nette\objectmixin' => '/Utils/ObjectMixin.php',
		'nette\paginator' => '/Utils/Paginator.php',
		'nette\recursivecomponentiterator' => '/ComponentModel/ComponentContainer.php',
		'nette\reflection\annotation' => '/Reflection/Annotation.php',
		'nette\reflection\annotationsparser' => '/Reflection/AnnotationsParser.php',
		'nette\reflection\classreflection' => '/Reflection/ClassReflection.php',
		'nette\reflection\extensionreflection' => '/Reflection/ExtensionReflection.php',
		'nette\reflection\functionreflection' => '/Reflection/FunctionReflection.php',
		'nette\reflection\iannotation' => '/Reflection/IAnnotation.php',
		'nette\reflection\methodreflection' => '/Reflection/MethodReflection.php',
		'nette\reflection\parameterreflection' => '/Reflection/ParameterReflection.php',
		'nette\reflection\propertyreflection' => '/Reflection/PropertyReflection.php',
		'nette\regexpexception' => '/Utils/String.php',
		'nette\safestream' => '/Utils/SafeStream.php',
		'nette\security\authenticationexception' => '/Security/AuthenticationException.php',
		'nette\security\iauthenticator' => '/Security/IAuthenticator.php',
		'nette\security\iauthorizator' => '/Security/IAuthorizator.php',
		'nette\security\identity' => '/Security/Identity.php',
		'nette\security\iidentity' => '/Security/IIdentity.php',
		'nette\security\iresource' => '/Security/IResource.php',
		'nette\security\irole' => '/Security/IRole.php',
		'nette\security\permission' => '/Security/Permission.php',
		'nette\security\simpleauthenticator' => '/Security/SimpleAuthenticator.php',
		'nette\servicelocator' => '/Environment/ServiceLocator.php',
		'nette\smartcachingiterator' => '/Utils/Iterators/SmartCachingIterator.php',
		'nette\string' => '/Utils/String.php',
		'nette\templates\basetemplate' => '/Templates/BaseTemplate.php',
		'nette\templates\cachinghelper' => '/Templates/Filters/CachingHelper.php',
		'nette\templates\ifiletemplate' => '/Templates/IFileTemplate.php',
		'nette\templates\itemplate' => '/Templates/ITemplate.php',
		'nette\templates\lattefilter' => '/Templates/Filters/LatteFilter.php',
		'nette\templates\lattemacros' => '/Templates/Filters/LatteMacros.php',
		'nette\templates\snippethelper' => '/Templates/Filters/SnippetHelper.php',
		'nette\templates\template' => '/Templates/Template.php',
		'nette\templates\templatecachestorage' => '/Templates/TemplateCacheStorage.php',
		'nette\templates\templatefilters' => '/Templates/Filters/TemplateFilters.php',
		'nette\templates\templatehelpers' => '/Templates/Filters/TemplateHelpers.php',
		'nette\tools' => '/Utils/Tools.php',
		'nette\web\html' => '/Web/Html.php',
		'nette\web\httpcontext' => '/Web/HttpContext.php',
		'nette\web\httprequest' => '/Web/HttpRequest.php',
		'nette\web\httpresponse' => '/Web/HttpResponse.php',
		'nette\web\httpuploadedfile' => '/Web/HttpUploadedFile.php',
		'nette\web\ihttprequest' => '/Web/IHttpRequest.php',
		'nette\web\ihttpresponse' => '/Web/IHttpResponse.php',
		'nette\web\iuser' => '/Web/IUser.php',
		'nette\web\session' => '/Web/Session.php',
		'nette\web\sessionnamespace' => '/Web/SessionNamespace.php',
		'nette\web\uri' => '/Web/Uri.php',
		'nette\web\uriscript' => '/Web/UriScript.php',
		'nette\web\user' => '/Web/User.php',
		'notimplementedexception' => '/Utils/exceptions.php',
		'notsupportedexception' => '/Utils/exceptions.php',
	);



	/**
	 * Returns singleton instance with lazy instantiation.
	 * @return NetteLoader
	 */
	public static function getInstance()
	{
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}



	/**
	 * Handles autoloading of classes or interfaces.
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		$type = ltrim(strtolower($type), '\\');
		if (isset($this->list[$type])) {
			LimitedScope::load(NETTE_DIR . $this->list[$type]);
			self::$count++;
		}
	}

}
