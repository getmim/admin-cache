<?php
/**
 * CacheController
 * @package admin-cache
 * @version 0.0.1
 */

namespace AdminCache\Controller;

use LibCacheOutput\Library\Callback;
use Mim\Library\Fs;
use LibForm\Library\Form;

class CacheController extends \Admin\Controller
{
    private function getMenuItems(){
        $items = [];

        if(module_exists('lib-cache')){
            $items[] = (object)[
                'id'    => 'data',
                'label' => 'Data Cache',
                'info'  => 'List of data cache for internal usage',
                'total' => count($this->cache->list()),
                'link'  => $this->router->to('adminCacheData')
            ];
        }

        if(module_exists('lib-cache-output')){
            // count cache total
            $base  = Callback::getCacheBase();
            $files = Fs::scan($base);
            $total = count($files);
            if(in_array('.gitkeep', $files))
                $total--;

            $items[] = (object)[
                'id'    => 'output',
                'label' => 'Output Cache',
                'info'  => 'List of data cache of rendered output',
                'total' => $total,
                'link'  => $this->router->to('adminCacheOutput')
            ];
        }

        return $items;
    }

    public function dataAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->cleanup_caches)
            return $this->show404();

        $form = new Form('admin.cache.blank');

        $params = [
            '_meta' => [
                'title' => 'System Settings',
                'menus' => ['admin-setting']
            ],
            'items' => $this->getMenuItems(),
            'form' => $form
        ];

        if(!$form->csrfTest('noob'))
            return $this->resp('cache/data', $params);

        $this->cache->truncate();

        $next = $this->router->to('adminCacheIndex', [], ['cleaned'=>1]);

        $this->res->redirect($next);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->cleanup_caches)
            return $this->show404();

        $params = [
            '_meta' => [
                'title' => 'System Settings',
                'menus' => ['admin-setting']
            ],
            'items' => $this->getMenuItems(),
            'cleaned' => $this->req->getQuery()
        ];

        $this->resp('cache/index', $params);
    }

    public function outputAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->cleanup_caches)
            return $this->show404();
        if(!module_exists('lib-cache-output'))
            return $this->show404();

        $form = new Form('admin.cache.blank');

        $params = [
            '_meta' => [
                'title' => 'System Settings',
                'menus' => ['admin-setting']
            ],
            'items' => $this->getMenuItems(),
            'form' => $form
        ];

        if(!$form->csrfTest('noob'))
            return $this->resp('cache/output', $params);

        $base  = Callback::getCacheBase();
        $files = Fs::scan($base);

        foreach($files as $file){
            if($file === '.gitkeep')
                continue;

            $file_abs = $base . '/' . $file;
            if(is_file($file_abs))
                unlink($file_abs);
            elseif(is_dir($file_abs))
                Fs::rmdir($file_abs);
        }

        $next = $this->router->to('adminCacheIndex', [], ['cleaned'=>1]);

        $this->res->redirect($next);
    }
}