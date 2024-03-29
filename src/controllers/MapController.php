<?php

namespace wsydney76\elementmap\controllers;

use Craft;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\GlobalSet;
use craft\elements\Tag;
use craft\elements\User;
use craft\commerce\elements\Product;
use craft\web\Controller;
use wsydney76\elementmap\ElementMap;
use yii\web\NotFoundHttpException;

class MapController extends Controller
{

    // Protected Properties
    // =========================================================================

    public function actionMap($site, $class, $id)
    {

        $element = null;
        switch ($class) {
            case 'entry': {
                $draftId = Craft::$app->request->getParam('draftId');

                if ($draftId) {
                    $element = Entry::find()->draftId($draftId)->provisionalDrafts(null)->anyStatus()->site('*')->preferSites([$site])->unique()->one();
                } else {
                    $element = Entry::find()->id($id)->anyStatus()->site('*')->preferSites([$site])->unique()->one();
                    if (!$element) {
                        $element = Entry::find()->drafts(true)->provisionalDrafts(null)->id($id)->anyStatus()->site('*')->preferSites([$site])->unique()->one();
                    }
                    if (!$element) {
                        $element = Entry::find()->revisions(true)->id($id)->anyStatus()->site('*')->preferSites([$site])->unique()->one();
                    }
                }

                break;
            }
            case 'asset': {
                $element = Asset::find()->site($site)->id($id)->one();
                break;
            }

            case 'category': {
                $element = Category::find()->id($id)->one();
                break;
            }

            case 'tag': {
                $element = Tag::find()->id($id)->one();
                break;
            }

            case 'user': {
                $element = User::find()->id($id)->one();
                break;
            }

            case 'globalset': {
                $element = GlobalSet::find()->id($id)->one();
                break;
            }

            case 'product': {
                $element = Product::find()->id($id)->one();
                break;
            }
        }

        if (!$element) {
            throw  new NotFoundHttpException("Element not found: {$class}/{$id}");
        }

        $plugin = ElementMap::getInstance();
        $map = $plugin->renderer->getElementMap($element, $element->siteId);


        return Craft::$app->view->renderTemplate('elementmap/_map_content', ['map' => $map]);;
    }

    // Public Methods
    // =========================================================================
    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|bool|int $allowAnonymous = [];
}
