<?php

namespace App\Controller;

class PageController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'home':
                        $this->home();
                        break;
                    case 'mentions':
                        $this->mentions();
                        break;
                    case 'about':
                        $this->about();
                        break;

                    case 'contact':
                        $this->contact();
                        break;

                    case 'faq':
                        $this->faq();
                        break;

                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function home()
    {
        $this->render('pages/home');
    }

    protected function mentions()
    {
        $this->render('pages/mentions', [
            'currentPage' => 'mentions'
        ]);
    }

    protected function about()
    {
        $this->render('pages/about');
    }

    protected function contact()
    {
        $this->render('pages/contact');
    }

    protected function faq()
    {
        $this->render('pages/faq');
    }
}
