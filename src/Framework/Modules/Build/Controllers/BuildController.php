<?php
namespace Framework\Modules\Build\Controllers;

use Framework\Modules\UI\Breadcrumb;
use Framework\Modules\UI\Fonts\FontAwesome;
use Framework\Modules\UI\Theme;
use Framework\Modules\UI\Views\DefaultBlankPageView;

class BuildController extends BuildControllerBase {

    public function dashboardAction() {

        Theme::addBreadcrumb(new Breadcrumb("Build"));
        Theme::addBreadcrumb(new Breadcrumb("Dashboard"));


        $oView = new DefaultBlankPageView();

        $oView->panZone()
            ->addRow()
                ->addColumn(4)
                    ->addPanel("panModel")
                        ->setHeaderLabel("Models")
                        ->setIcon(FontAwesome::FAS_CUBE)
                            ->addParagraph()
                                ->setText("Define application data models")
                                ->getParent()
                            ->addButton("btnListModel")
                                ->setLabel("List models")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/model/list')")
                                ->getParent()
                            ->addButton("btnNewModel")
                                ->setLabel("New model")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/model/edit')")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(4)
                    ->addPanel("panView")
                        ->setHeaderLabel("Views")
                        ->setIcon(FontAwesome::FAS_FILE_INVOICE)
                            ->addParagraph()
                                ->setText("Define application views")
                                ->getParent()
                            ->addButton("btnListView")
                                ->setLabel("List views")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/view/list')")
                                ->getParent()
                            ->addButton("btnNewView")
                                ->setLabel("New view")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/view/edit');")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(4)
                    ->addPanel("panController")
                        ->setHeaderLabel("Controllers")
                        ->setIcon(FontAwesome::FAS_BOLT)
                            ->addParagraph()
                                ->setText("Define application controllers")
                                ->getParent()
                            ->addButton("btnListControllers")
                                ->setLabel("List controllers")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/controller/list')")
                                ->getParent()
                            ->addButton("btnNewController")
                                ->setLabel("New controller")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/controller/edit');")
                                ->getParent()
                        ->getParent()
                    ->getParent();



        $oView->panZone()
            ->addRow()
                ->addColumn(4)
                    ->addPanel("panDataSet")
                        ->setHeaderLabel("DataSet")
                        ->setIcon(FontAwesome::FAS_CUBES)
                            ->addButton("btnListDataSet")
                                ->setLabel("List DataSets")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/dataset/list')")
                                ->getParent()
                            ->addButton("btnNewDataSet")
                                ->setLabel("New DataSet")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/dataset/edit')")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(4)
                    ->addPanel("panPolicies")
                        ->setHeaderLabel("Policies")
                        ->setIcon(FontAwesome::FAS_LOCK)
                            ->addButton("btnListPolicies")
                                ->setLabel("List policies")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/policy/list')")
                                ->getParent()
                            ->addButton("btnNewPolicy")
                                ->setLabel("New policy")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/policy/edit');")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(4)
                    ->addPanel("panReports")
                        ->setHeaderLabel("Reports")
                        ->setIcon(FontAwesome::FAS_BOOK_OPEN)
                            ->addButton("btnListReports")
                                ->setLabel("List reports")
                                ->setIcon(FontAwesome::FAR_LIST_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/report/list')")
                                ->getParent()
                            ->addButton("btnNewReport")
                                ->setLabel("New report")
                                ->setIcon(FontAwesome::FAR_FILE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/report/edit');")
                                ->getParent()
                        ->getParent()
                    ->getParent();



        /**
         * Add tools
         */
        $oView->panZone()
            ->addRow()
                ->addColumn(3)
                    ->addPanel("panTranslation")
                        ->setHeaderLabel("Translation")
                        ->setIcon(FontAwesome::FAS_LANGUAGE)
                            ->addButton("btnTranslations")
                                ->setLabel("Translations")
                                ->setIcon(FontAwesome::FAS_LANGUAGE)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/translation/list')")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(3)
                    ->addPanel("panVersions")
                        ->setHeaderLabel("Versions")
                        ->setIcon(FontAwesome::FAS_LIST_OL)
                            ->addButton("btnVersions")
                                ->setLabel("Versions")
                                ->setIcon(FontAwesome::FAS_LIST_OL)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/version/list')")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(3)
                    ->addPanel("panReleaseNotes")
                        ->setHeaderLabel("Release notes")
                        ->setIcon(FontAwesome::FAS_FILE_ALT)
                              ->addButton("btnReleaseNotes")
                                ->setLabel("Release notes")
                                ->setIcon(FontAwesome::FAS_FILE_ALT)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/releasenote/list')")
                                ->getParent()
                        ->getParent()
                    ->getParent()
                ->addColumn(3)
                    ->addPanel("panHelp")
                        ->setHeaderLabel("Help")
                        ->setIcon(FontAwesome::FAS_QUESTION)
                              ->addButton("btnHelp")
                                ->setLabel("Help")
                                ->setIcon(FontAwesome::FAS_QUESTION)
                                ->setOnClick("Framework.modules.Navigation.redirect('/build/help/list')")
                                ->getParent()
                        ->getParent()
                    ->getParent();


        return $oView->getView();

    }



}

?>