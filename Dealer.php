<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace Dealer;

use Dealer\Model\DealerContactInfoQuery;
use Dealer\Model\DealerContactQuery;
use Dealer\Model\DealerContentQuery;
use Dealer\Model\DealerFolderQuery;
use Dealer\Model\DealerQuery;
use Dealer\Model\DealerShedulesQuery;
use Symfony\Component\Finder\Finder;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Model\Resource;
use Thelia\Model\ResourceQuery;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

/**
 * Class Dealer
 * @package Dealer
 */
class Dealer extends BaseModule
{
    const MESSAGE_DOMAIN = "dealer";
    const ROUTER = "router.dealer";

    const RESOURCES_DEALER = "admin.dealer";
    const RESOURCES_CONTACT = "admin.dealer.contact";
    const RESOURCES_SCHEDULES = "admin.dealer.schedules";
    const RESOURCES_GEO = "admin.dealer.geo";
    const RESOURCES_ASSOCIATED = "admin.dealer.associated";
    const RESOURCES_USERS = "admin.dealer.users";
    const RESOURCES_MODULE = "admin.dealer.module";

    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            DealerQuery::create()->findOne();
            DealerContactInfoQuery::create()->findOne();
            DealerContactQuery::create()->findOne();
            DealerShedulesQuery::create()->findOne();
            DealerContentQuery::create()->findOne();
            DealerFolderQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
        }

        $this->addResource(self::RESOURCES_DEALER);
        $this->addResource(self::RESOURCES_CONTACT);
        $this->addResource(self::RESOURCES_SCHEDULES);
        $this->addResource(self::RESOURCES_GEO);
        $this->addResource(self::RESOURCES_ASSOCIATED);
        $this->addResource(self::RESOURCES_USERS);
        $this->addResource(self::RESOURCES_MODULE);
    }

    /**
     * @inheritDoc
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $finder = (new Finder)
            ->files()
            ->name('#.*?\.sql#')
            ->in(__DIR__ . DS . 'Setup' . DS . 'sql');

        $database = new Database($con);

        /** @var \Symfony\Component\Finder\SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(
                    null,
                    [
                        $updateSQLFile->getPathname()
                    ]
                );
            }
        }
    }

    public function getHooks()
    {
        return [
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.extra.content.edit",
                "title" => "Dealer Extra Content",
                "description" => [
                    "en_US" => "Allow you to insert element in modules tab on Dealer edit page",
                    "fr_FR" => "Permet l'ajout de contenu sur la partie module de l'edition",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.edit.js",
                "title" => "Dealer Edit Extra Js",
                "description" => [
                    "en_US" => "Allow you to insert js on Dealer edit page",
                    "fr_FR" => "Permet l'ajout de js sur l'edition",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.js",
                "title" => "Dealer Extra Js",
                "description" => [
                    "en_US" => "Allow you to insert js on Dealer list",
                    "fr_FR" => "Permet l'ajout de js sur la liste",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.additional",
                "title" => "Dealer Extra Tab",
                "description" => [
                    "en_US" => "Allow you to insert a tab on Dealer edit page",
                    "fr_FR" => "Permet l'ajout d'une page sur l'edition d'un point de vente",
                ],
                "active" => true,
                "block" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.edit.nav.bar",
                "title" => "Dealer Edition NavBar Link",
                "description" => [
                    "en_US" => "Allow you to insert link between arrow previous and next on edtion view",
                    "fr_FR" => "Permet l'ajout d'un lien sur la page d'édition entre les liens suivant et précedent",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "dealer.associated.tabcontent",
                "title" => "Dealer Associated Nav Tab",
                "description" => [
                    "en_US" => "Allow you to insert association content",
                    "fr_FR" => "Permet l'ajout de contenu dans la table d'association",
                ],
                "active" => true,
            ),
        ];
    }

    protected function addResource($code)
    {
        if(null === ResourceQuery::create()->findOneByCode($code)){
            $resource = new Resource();
            $resource->setCode($code);
            $resource->setTitle($code);
            $resource->save();
        }
    }
}
