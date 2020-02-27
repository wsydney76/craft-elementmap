<?php

namespace wsydney76\elementmap\controllers;

use craft\elements\Entry;
use craft\web\Controller;
use wsydney76\elementmap\ElementMap;
use yii\web\NotFoundHttpException;

class MapController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    public function actionMap($class, $id) {
        $entry = Entry::find()->id($id)->anyStatus()->site('*')->unique()->one();
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        $plugin = ElementMap::getInstance();
        $map = $plugin->renderer->getElementMap($entry, $entry->siteId);
        return $plugin->renderMap($map);
    }
}
