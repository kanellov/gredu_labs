<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Admin\Action\Schools;

use GrEduLabs\Schools\Service\SchoolServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ListSchools
{
    /**
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var SchoolServiceInterface
     */
    protected $schoolService;

    /**
     * Constructor.
     *
     * @param Twig $view
     */
    public function __construct(Twig $view, SchoolServiceInterface $schoolService)
    {
        $this->view          = $view;
        $this->schoolService = $schoolService;
    }

    public function __invoke(Request $req, Response $res)
    {
        $schools = $this->schoolService->findSchools();

        return $this->view->render($res, 'admin/schools/list_schools.twig', [
            'schools' => $schools,
        ]);
    }
}
