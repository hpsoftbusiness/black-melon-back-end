<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Programmers; // Update the namespace to match the entity
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class ProgrammersAdmin extends Admin
{
    final public const PROGRAMMER_LIST_KEY = 'programmers';
    final public const PROGRAMMER_FORM_KEY = 'programmers_details';
    final public const PROGRAMMER_LIST_VIEW = 'app.programmers_list';
    final public const PROGRAMMER_ADD_FORM_VIEW = 'app.programmers_add_form';
    final public const PROGRAMMER_EDIT_FORM_VIEW = 'app.programmers_edit_form';

    public function __construct(
        private readonly ViewBuilderFactoryInterface $viewBuilderFactory,
        private readonly WebspaceManagerInterface $webspaceManager,
    ) {
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $module = new NavigationItem('Programmers'); // Update the navigation item label
        $module->setPosition(40);
        $module->setIcon('fa-user'); // Change the icon to a user icon

        // Configure a NavigationItem with a View
        $programmers = new NavigationItem('Programmers'); // Update the navigation item label
        $programmers->setPosition(10);
        $programmers->setView(static::PROGRAMMER_LIST_VIEW);

        $module->addChild($programmers);

        $navigationItemCollection->add($module);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure Programmer List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::PROGRAMMER_LIST_VIEW, '/programmers/:locale')
            ->setResourceKey(Programmers::RESOURCE_KEY)
            ->setListKey(self::PROGRAMMER_LIST_KEY)
            ->setTitle('Programmers') // Update the title
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::PROGRAMMER_ADD_FORM_VIEW)
            ->setEditView(static::PROGRAMMER_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure Programmer Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::PROGRAMMER_ADD_FORM_VIEW, '/programmers/:locale/add')
            ->setResourceKey(Programmers::RESOURCE_KEY)
            ->setBackView(static::PROGRAMMER_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::PROGRAMMER_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Programmers::RESOURCE_KEY)
            ->setFormKey(self::PROGRAMMER_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::PROGRAMMER_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::PROGRAMMER_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure Programmer Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::PROGRAMMER_EDIT_FORM_VIEW, '/programmers/:locale/:id')
            ->setResourceKey(Programmers::RESOURCE_KEY)
            ->setBackView(static::PROGRAMMER_LIST_VIEW)
            ->setTitleProperty('programmers')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::PROGRAMMER_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Programmers::RESOURCE_KEY)
            ->setFormKey(self::PROGRAMMER_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::PROGRAMMER_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);
    }
}
