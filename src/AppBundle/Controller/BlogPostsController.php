<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Repository\BlogPostRepository;
use AppBundle\Form\Type\BlogPostType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class BlogPostsController
 * @package AppBundle\Controller
 *
 * @RouteResource("post")
 */
class BlogPostsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Blog Post
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @ApiDoc(
     *     output="AppBundle\Entity\BlogPost",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction(int $id)
    {

        $blogPost = $this->getBlogPostRepository()->createFindOneByIdQuery($id)->getSingleResult();
        
        if ($blogPost === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        
        return $blogPost;
    }

    /**
     * Gets a collection of BlogPosts
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\BlogPost",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        $orderByDate = false;
        $orderByTitle = false;
        $filterByDate = null;
        $filterByTitle = null;
        if (isset($_GET['orderByDate']))
        {
            $orderByDate = $_GET['orderByDate'];
        }
        if (isset($_GET['orderByTitle']))
        {
            $orderByTitle = $_GET['orderByTitle'];
        }
        if (isset($_GET['filterByDate']))
        {
            $filterByDate = $_GET['filterByDate'];
        }
        if (isset($_GET['filterByTitle']))
        {
            $filterByTitle = $_GET['filterByTitle'];
        }

        if (null != $filterByDate && null != $filterByTitle)
        {
            if ($orderByDate == true && $orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateAndTitleSortedByDateAndTitleQuery($filterByDate, $filterByTitle)->getResult();
            }
            elseif ($orderByDate == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateAndTitleSortedByDateQuery($filterByDate, $filterByTitle)->getResult();
            }
            elseif ($orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateAndTitleSortedByTitleQuery($filterByDate, $filterByTitle)->getResult();
            }
            else
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateAndTitleQuery($filterByDate, $filterByTitle)->getResult();
            }
        }
        elseif (null != $filterByDate)
        {
            if ($orderByDate == true && $orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateSortedByDateAndTitleQuery($filterByDate)->getResult();
            }
            elseif ($orderByDate == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateSortedByDateQuery($filterByDate)->getResult();
            }
            elseif ($orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateSortedByTitleQuery($filterByDate)->getResult();
            }
            else
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByDateQuery($filterByDate)->getResult();
            }
        }
        elseif (null != $filterByTitle)
        {
            if ($orderByDate == true && $orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByTitleSortedByDateAndTitleQuery($filterByTitle)->getResult();
            }
            elseif ($orderByDate == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByTitleSortedByDateQuery($filterByTitle)->getResult();
            }
            elseif ($orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByTitleSortedByTitleQuery($filterByTitle)->getResult();
            }
            else
            {
                $result = $this->getBlogPostRepository()->createFindAllFilteredByTitleQuery($filterByTitle)->getResult();
            }
        }
        else
        {
            if ($orderByDate == true && $orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllByIdSortByDateAndTitleQuery()->getResult();
            }
            elseif ($orderByDate == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllByIdSortByDateQuery()->getResult();
            }
            elseif ($orderByTitle == true)
            {
                $result = $this->getBlogPostRepository()->createFindAllByIdSortByTitleQuery()->getResult();
            }
            else
            {
                $result = $this->getBlogPostRepository()->createFindAllQuery()->getResult();
            }
        }
        if (null == $result)
        {
            throw new NotFoundHttpException('No Result to display!');
        }
        else
        {
            return $result;
        }
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\BlogPostType",
     *     output="AppBundle\Entity\BlogPost",
     *     statusCodes={
     *         201 = "Returned when a new BlogPost has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(BlogPostType::class, null, [
            'csrf_protection' => false,        
        ]);
        
        $form->submit($request->request->all());
        
        if (!$form->isValid()) {
            return $form;
        }

        /**
         * @var $blogPost BlogPost
         */
        $blogPost = $form->getData();
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        $routeOptions = [
            'id' => $blogPost->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('get_post', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\BlogPostType",
     *     output="AppBundle\Entity\BlogPost",
     *     statusCodes={
     *         204 = "Returned when an existing BlogPost has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, int $id)
    {
        /**
         * @var $blogPost BlogPost
         */
        $blogPost = $this->getBlogPostRepository()->find($id);

        if ($blogPost === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(BlogPostType::class, $blogPost, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $blogPost->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('get_post', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\BlogPostType",
     *     output="AppBundle\Entity\BlogPost",
     *     statusCodes={
     *         204 = "Returned when an existing BlogPost has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function patchAction(Request $request, int $id)
    {
        /**
         * @var $blogPost BlogPost
         */
        $blogPost = $this->getBlogPostRepository()->find($id);

        if ($blogPost === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(BlogPostType::class, $blogPost, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $blogPost->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('get_post', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing BlogPost has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction(int $id)
    {
        /**
         * @var $blogPost BlogPost
         */
        $blogPost = $this->getBlogPostRepository()->find($id);

        if ($blogPost === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($blogPost);
        $em->flush();

        return new View(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return BlogPostRepository
     */
    private function getBlogPostRepository()
    {
        return $this->get('crv.doctrine_entity_repository.blog_post');
    }
}
