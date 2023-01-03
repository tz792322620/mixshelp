<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\model\Category;
use app\common\model\Article;
use app\admin\model\submit\Request;
use think\Cookie;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        $think_var = Cookie::get('think_var');

        $Category = Category::where('pid',0)->select();
        $Article = Article::where('type',$think_var)->limit(10)->order('updatetime desc')->select();

        $this->view->assign("Article", $Article);
        $this->view->assign("pidList", $Category);
        return $this->view->fetch();
    }

    public function article()
    {
        $think_var = Cookie::get('think_var');
        $id = $this->request->get("id/d", 0);
        $Article = Article::where('type',$think_var)->where('id',$id)->find();
        $pid = Category::where('id',$Article['category_id'])->find();
        $pids = Category::where('id',$pid['pid'])->find();

        $this->view->assign("pid", $pid);
        $this->view->assign("pids", $pids);
        $this->view->assign("Article", $Article);
        return $this->view->fetch();
    }

    public function category()
    {
        $id = $this->request->get("id/d", 0);
        $Category = Category::where('id',$id)->find();
        $Categorys = Category::where('pid',$id)->select();
        $think_var = Cookie::get('think_var');
        foreach ($Categorys as $key => $v){
            $Categorys[$key]['articles'] = Article::where('type',$think_var)->where('category_id',$v['id'])->order('updatetime desc')->select();


        }

        $this->view->assign("Categorys", $Categorys);
        $this->view->assign("Category", $Category);
        return $this->view->fetch();
    }

    public function sections()
    {
        $think_var = Cookie::get('think_var');
        $id = $this->request->get("id/d", 0);
        $Category = Category::where('id',$id)->find();
        $pid = Category::where('id',$Category['pid'])->find();

        $Category['articles'] = Article::where('type',$think_var)->where('category_id',$Category['id'])->order('updatetime desc')->select();

//        var_dump($Category);
        $this->view->assign("pid", $pid);
        $this->view->assign("Category", $Category);
        return $this->view->fetch();
    }


    public function search()
    {
        $think_var = Cookie::get('think_var');
        $query = $this->request->get("query/s", 0);
        $article = Article::where('content','like','%' . $query . '%')->order('updatetime desc')->select();
//        var_dump($article);
        foreach ($article as $k=>$v){
            $article[$k]['content'] = strip_tags($v['content']);
        }
        $this->view->assign("query", $query);
        $this->view->assign("article", $article);
        return $this->view->fetch();
    }

    public function requests()
    {
        if($this->request->isPost()){
            $params = $this->request->post();
            $e['lianxi'] = $params['Email'];
            $e['content'] = $params['content'];
            Request::create($e);
            $this->success(__('已经提交请求'), url('index/index'));
        }

        return $this->view->fetch();
    }

}
