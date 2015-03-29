class AcmeController
use HRPROJECT\FileManagerBundle\Entity\Asset;
use HRPROJECT\FileManagerBundle\Entity\Document;
use HRPROJECT\FileManagerBundle\Service\AssetManager;
use HRPROJECT\FileManagerBundle\Service\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

