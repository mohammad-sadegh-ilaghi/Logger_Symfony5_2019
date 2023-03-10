<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use Doctrine\Migrations\Exception\NoTablesFound;
use http\Exception\InvalidArgumentException;
use PHPUnit\TextUI\XmlConfiguration\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class ArticlesController
 * @IsGranted ("ROLE_ADMIN")
 */
class ArticlesController extends AbstractController
{
    public function list_article(Request $request) {
        return $this->render('article/list_article.html.twig',['list_article'=> $this->getDoctrine()->getRepository(Article::class) ->findBy(['status'=>true])]);
    }


    public function add_article(Request $request, SluggerInterface $slugger) {
        $article = new Article();
        $categories = $this -> getDoctrine() -> getRepository(Category::class)->findAll();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if ( $request->get('category_article') == null  ) {
                $this->addFlash('category_article', 'باید دسته بندی رو انتخاب کنید');
                return $this->render('article/add_article.html.twig', ['form' => $form->createView(),'categories' => $categories]);
            }
            try {
                $image = $request->files->get('article')['image'];
            }
            catch (\Exception $exception){
                throw  new NoTablesFound();
            }
            if ($image) {
                $upload_file = $this->getParameter('uploads_image_article');
                $file_name = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $upload_file,
                    $file_name
                );
                $article->setImage($file_name);
            }
            else {
                throw new NotFoundHttpException();
            }
            $category = $this->getDoctrine()->getRepository(Category::class)->find((int)$request->get('category_article'));
            if ($category == null) {
                $this->addFlash('category_article', 'دسته بندی موجود وجود ندارد.');
                return $this->render('article/add_article.html.twig', ['form' => $form->createView(),'categories' => $categories]);
            }
            $article->setCategory($category);
            if ($request->get('publish') == null) {
                $article->setPublish(false);
            }
            else {
                $article->setPublish(true);
            }
            if ($article->getTitle() == null){
                $form->get('title')->addError(new FormError('لطفا title رو وارد کنید'));
                return $this->render('article/add_article.html.twig', ['form' => $form->createView(),'categories' => $categories]);
            }
            $article->setStatus(true);
            $article->setAuther($this->getUser());
            $article->setDate(new \DateTime('UTC'));
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
            return  $this->redirectToRoute('list_article');
        }
        return $this->render('article/add_article.html.twig', ['form' => $form->createView(),'categories' => $categories]);
    }

    public function delete_article(Request $request) {
        if ($request->get('id') == null) {
            throw new InvalidArgumentException();
        }
        $article = $this->getDoctrine()->getRepository(Article::class)->find((int) $request->get('id'));
        if ($article == null) {
            throw new NotFoundHttpException();
        }
        $article->setStatus(false);
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('list_article');
    }
    public function change_status_article(Request $request) {
        if ($request->get('id') == null) {
            throw new InvalidArgumentException();
        }
        $article = $this->getDoctrine()->getRepository(Article::class)->find((int) $request->get('id'));
        if ($article == null) {
            throw new NotFoundHttpException();
        }
        $article->setPublish(!$article->getPublish());
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('list_article');
    }


    public function edit_article(Request  $request) {
        if ($request->get('id') == null) {
            throw new InvalidArgumentException();
        }
        $article = $this->getDoctrine()->getRepository(Article::class) ->find((int) $request->get('id'));
        if ($article == null) {
            throw new NotFoundHttpException('مقاله وجود ندارد.');
        }
        $categories =  $this->getDoctrine()->getRepository(Category::class)->findAll();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $article->setPublish(false);
            if ($request->get('publish') != null) {
                $article->setPublish(true);
            }
            try {
                $image = $request->files->get('article')['image'];
            }
            catch (\Exception $exception){
                throw  new NoTablesFound();
            }
            if ($image) {
                $upload_file = $this->getParameter('uploads_image_article');
                $file_name = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $upload_file,
                    $file_name
                );
                $article->setImage($file_name);
            }

            if ($request->get('category_article') == null) {
                throw new InvalidArgumentException('شما دسته بندی مورد نظر باید بگوید');
            }
            $category = $this->getDoctrine()->getRepository(Category::class)->find((int) $request->get('category_article'));
            if ($category == null) {
                throw new NotFoundHttpException('دسته بندی وارد شده اشتباه است.');
            }
            $article->setCategory($category);
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
            return  $this->redirectToRoute('list_article');
        }
        $img = new File($form->get('image')->getData());
        return $this->render('article/edit_article.html.twig', [
            'form'=>$form->createView(),
            'categories'=>$categories,
            'id'=> $article->getId(),
            'image' =>$img
            ]);
    }
}