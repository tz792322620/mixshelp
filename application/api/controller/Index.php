<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Article;
use app\common\model\Category;
use think\Cookie;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }


    /**
     * 获取分类文章列表
     *
     */
    public function get_category_articles()
    {
        $id = $this->request->get("id/d", 0);
        $lang = $this->request->get("lang/s", 0);
        if(!$lang){
            $lang = 'zh-cn';
        }
        $data = Article::where('type',$lang)->where('category_id',$id)->order('updatetime desc')->field('id,category_id,title,updatetime')->select();
        $this->success('请求成功',$data);
    }


    /**
     * 获取分类文章
     *
     */
    public function get_articles()
    {
        $id = $this->request->get("id/d", 0);
        $data = Article::where('id',$id)->find();
        $this->success('请求成功',$data);
    }


    /**
     * 获取分类
     *
     */
    public function get_category()
    {
        $data = Category::where('pid','=',0)->field('id,name,updatetime,type,flag')->select();
        $this->success('请求成功',$data);
    }
}
