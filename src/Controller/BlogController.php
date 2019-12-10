<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class BlogController extends AbstractController
{

    /**
     * @Route("/blog/{page}", name="allblog",defaults={"page"=1},requirements={"page"="\d+"})
     */

    public function list($page=1,Request $request)
    {
        $limit=$request->get('limit',10);
        $repository=$this->getDoctrine()->getRepository(BlogPost::class);
        $item=$repository->findAll();
       return $this->json(
            [
                'page'=>$page,
                'date'=>array_map(function (BlogPost $item)
                {
                    return $this->generateUrl("blog_by_slug",['slug'=>$item->getSlug()]);
                },$item)

            ]
        );
    }
/**
     * @Route("/blog/post/{id}", name="blog_by_id",requirements={"id"="\d+"},methods={"GET"})
 * @ParamConverter("post",class="App:BlogPost")
     */
    public function post($post)
    {
        return $this->json($post);
    }
/**
     * @Route("/blog/post/{slug}", name="blog_by_slug",methods={"GET"})
 * @ParamConverter("post",class="App:BlogPost",options={"mapping":{"slug":"slug"}})
     */
    public function postBySlag($post)
    {
        return $this->json($post  );
    }

    /**
     * @Route("/blog/add",name="newblog",methods={"POST"})
     */
    public function add(Request $request)
    {
        /**@var Serializer $serializer**/
    $serializer =$this->get('serializer');
    $blog=$serializer->deserialize($request->getContent(),BlogPost::class,'json');
    $em=$this->getDoctrine()->getManager();
    $em->persist($blog);
    $em->flush();
    return $this->json($blog);
    }

    /**
     * @Route("/blog/post/{id}",name="blog_delete",methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }
}
