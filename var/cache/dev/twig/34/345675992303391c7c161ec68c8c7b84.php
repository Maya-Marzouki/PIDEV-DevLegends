<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* base.html.twig */
class __TwigTemplate_76dbbbc178281565daf91038e6d7b2aa extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">

<head>
    <meta charset=\"utf-8\">
    <title> ";
        // line 6
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        yield " </title>
    <meta content=\"width=device-width, initial-scale=1.0\" name=\"viewport\">
    <meta content=\"\" name=\"keywords\">
    <meta content=\"\" name=\"description\">

    <!-- Favicon -->
    <link href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/img/logoinnerbloom.png"), "html", null, true);
        yield "\" rel=\"icon\">

    <!-- Google Web Fonts -->
    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap\" rel=\"stylesheet\">  

    <!-- Icon Font Stylesheet -->
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css\" rel=\"stylesheet\">
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css\" rel=\"stylesheet\">

    <!-- Libraries Stylesheet -->
    <link href=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/animate/animate.min.css"), "html", null, true);
        yield "\" rel=\"stylesheet\">
    <link href=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/owlcarousel/assets/owl.carousel.min.css"), "html", null, true);
        yield "\" rel=\"stylesheet\">
    <link href=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/lightbox/css/lightbox.min.css"), "html", null, true);
        yield "\" rel=\"stylesheet\">

    <!-- Customized Bootstrap Stylesheet -->
    <link href=\"";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/css/bootstrap.min.css"), "html", null, true);
        yield "\" rel=\"stylesheet\">

    <!-- Template Stylesheet -->
    <link href=\"";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/css/style.css"), "html", null, true);
        yield "\" rel=\"stylesheet\">
</head>

<body> 
    <!-- Spinner Start -->
    <div id=\"spinner\" class=\"show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center\">
        <div class=\"spinner-border text-primary\" role=\"status\" style=\"width: 3rem; height: 3rem;\"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class=\"container-fluid bg-dark text-light px-0 py-2\">
        <div class=\"row gx-0 d-none d-lg-flex\">
            <div class=\"col-lg-7 px-5 text-start\">
                <div class=\"h-100 d-inline-flex align-items-center me-4\">
                    <span class=\"fa fa-phone-alt me-2\"></span>
                    <span>+216 21 345 678</span>
                </div>
                <div class=\"h-100 d-inline-flex align-items-center\">
                    <span class=\"far fa-envelope me-2\"></span>
                    <span>InnerBloom@gmail.com</span>
                </div>
            </div>
            <div class=\"col-lg-5 px-5 text-end\">
                <div class=\"h-100 d-inline-flex align-items-center mx-n2\">
                    <span>Follow Us:</span>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-facebook-f\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-twitter\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-linkedin-in\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-instagram\"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class=\"navbar navbar-expand-lg bg-white navbar-light sticky-top p-0\">
        <a href=\"base.html.twig\" class=\"navbar-brand d-flex align-items-center px-4 px-lg-5\">
            <h1 class=\"m-0\">InnerBloom</h1>
        </a>
        <button type=\"button\" class=\"navbar-toggler me-4\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarCollapse\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\" id=\"navbarCollapse\">
            <div class=\"navbar-nav ms-auto p-4 p-lg-0\">
                <a href=\"base.html.twig\" class=\"nav-item nav-link\">Home</a>
                <a href=\"about.html\" class=\"nav-item nav-link\">About</a>
                <a href=\"service.html\" class=\"nav-item nav-link\">Services</a>
                <a href=\"project.html\" class=\"nav-item nav-link\">Projects</a>
                <div class=\"nav-item dropdown\">
                    <a href=\"#\" class=\"nav-link dropdown-toggle\" data-bs-toggle=\"dropdown\">Pages</a>
                    <div class=\"dropdown-menu bg-light m-0\">
                        <a href=\"feature.html\" class=\"dropdown-item\">Features</a>
                        <a href=\"quote.html\" class=\"dropdown-item\">Free Quote</a>
                        <a href=\"team.html\" class=\"dropdown-item\">Our Team</a>
                        <a href=\"testimonial.html\" class=\"dropdown-item\">Testimonial</a>
                        <a href=\"404.html\" class=\"dropdown-item\">404 Page</a>
                    </div>
                </div>
                <a href=\"contact.html\" class=\"nav-item nav-link\">Contact</a>
            </div>
            <a href=\"\" class=\"btn btn-primary py-4 px-lg-4 rounded-0 d-none d-lg-block\">Login<i class=\"fa fa-arrow-right ms-3\"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class=\"container-fluid p-0 wow fadeIn\" data-wow-delay=\"0.1s\">
        <div id=\"header-carousel\" class=\"carousel slide\" data-bs-ride=\"carousel\">
            <div class=\"carousel-inner\">
                <div class=\"carousel-item active\">
                    <img class=\"w-100\" src=\"";
        // line 107
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/img/carousel-1.jpeg"), "html", null, true);
        yield "\" alt=\"Image\">
                    <div class=\"carousel-caption\">
                        <div class=\"container\">
                            <div class=\"row justify-content-center\">
                                <div class=\"col-lg-8\">
                                    <h1 class=\"display-1 text-white mb-5 animated slideInDown\">Make your inner well-being bloom</h1>
                                    <a href=\"\" class=\"btn btn-primary py-sm-3 px-sm-4\">Explore More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"carousel-item\">
                    <img class=\"w-100\" src=\"";
        // line 120
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/img/carousel-1.jpeg"), "html", null, true);
        yield "\" alt=\"Image\">
                    <div class=\"carousel-caption\">
                        <div class=\"container\">
                            <div class=\"row justify-content-center\">
                                <div class=\"col-lg-7\">
                                    <h1 class=\"display-1 text-white mb-5 animated slideInDown\">You deserve to be happy</h1>
                                    <a href=\"\" class=\"btn btn-primary py-sm-3 px-sm-4\">Explore More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#header-carousel\"
                data-bs-slide=\"prev\">
                <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
                <span class=\"visually-hidden\">Previous</span>
            </button>
            <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#header-carousel\"
                data-bs-slide=\"next\">
                <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
                <span class=\"visually-hidden\">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->
";
        // line 146
        yield from $this->unwrap()->yieldBlock('body', $context, $blocks);
        // line 149
        yield "    <!-- Footer Start -->
    <div class=\"container-fluid bg-dark text-light footer mt-5 py-5 wow fadeIn\" data-wow-delay=\"0.1s\">
        <div class=\"container py-5\">
            <div class=\"row g-5\">
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Our Office</h4>
                    <p class=\"mb-2\"><i class=\"fa fa-map-marker-alt me-3\"></i>123 Street, Ariana, Tunisia</p>
                    <p class=\"mb-2\"><i class=\"fa fa-phone-alt me-3\"></i>+216 21 345 678</p>
                    <p class=\"mb-2\"><i class=\"fa fa-envelope me-3\"></i>InnerBloom@gmail.com</p>
                    <div class=\"d-flex pt-2\">
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-twitter\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-facebook-f\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-youtube\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-linkedin-in\"></i></a>
                    </div>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Services</h4>
                    <a class=\"btn btn-link\" href=\"\">Landscaping</a>
                    <a class=\"btn btn-link\" href=\"\">Pruning plants</a>
                    <a class=\"btn btn-link\" href=\"\">Urban Gardening</a>
                    <a class=\"btn btn-link\" href=\"\">Garden Maintenance</a>
                    <a class=\"btn btn-link\" href=\"\">Green Technology</a>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Quick Links</h4>
                    <a class=\"btn btn-link\" href=\"\">About Us</a>
                    <a class=\"btn btn-link\" href=\"\">Contact Us</a>
                    <a class=\"btn btn-link\" href=\"\">Our Services</a>
                    <a class=\"btn btn-link\" href=\"\">Terms & Condition</a>
                    <a class=\"btn btn-link\" href=\"\">Support</a>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Newsletter</h4>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class=\"position-relative w-100\">
                        <input class=\"form-control bg-light border-light w-100 py-3 ps-4 pe-5\" type=\"text\" placeholder=\"Your email\">
                        <button type=\"button\" class=\"btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2\">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class=\"container-fluid copyright py-4\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-md-6 text-center text-md-start mb-3 mb-md-0\">
                    &copy; <a class=\"border-bottom\" href=\"#\">Your Site Name</a>, All Right Reserved.
                </div>
                <div class=\"col-md-6 text-center text-md-end\">
                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from \"https://htmlcodex.com/credit-removal\". Thank you for your support. ***/-->
                    Designed By <a class=\"border-bottom\" href=\"https://htmlcodex.com\">HTML Codex</a> Distributed By <a href=\"https://themewagon.com\">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href=\"#\" class=\"btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top\"><i class=\"bi bi-arrow-up\"></i></a>


    <!-- JavaScript libraries -->
    <script src=\"https://code.jquery.com/jquery-3.4.1.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js\"></script>
    <script src=\"";
        // line 219
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/wow/wow.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 220
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/easing/easing.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 221
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/waypoints/waypoints.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 222
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/owlcarousel/owl.carousel.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 223
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/counterup/counterup.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 224
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/parallax/parallax.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 225
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/isotope/isotope.pkgd.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 226
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/lib/lightbox/js/lightbox.min.js"), "html", null, true);
        yield "\"></script>

    <!-- Template Javascript -->
    <script src=\"";
        // line 229
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("client/js/main.js"), "html", null, true);
        yield "\"></script>
</body>

</html>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 6
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield " InnerBloom ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 146
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 147
        yield "
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "base.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  383 => 147,  370 => 146,  347 => 6,  332 => 229,  326 => 226,  322 => 225,  318 => 224,  314 => 223,  310 => 222,  306 => 221,  302 => 220,  298 => 219,  226 => 149,  224 => 146,  195 => 120,  179 => 107,  101 => 32,  95 => 29,  89 => 26,  85 => 25,  81 => 24,  66 => 12,  57 => 6,  50 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">

<head>
    <meta charset=\"utf-8\">
    <title> {% block title %} InnerBloom {% endblock %} </title>
    <meta content=\"width=device-width, initial-scale=1.0\" name=\"viewport\">
    <meta content=\"\" name=\"keywords\">
    <meta content=\"\" name=\"description\">

    <!-- Favicon -->
    <link href=\"{{asset('client/img/logoinnerbloom.png')}}\" rel=\"icon\">

    <!-- Google Web Fonts -->
    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap\" rel=\"stylesheet\">  

    <!-- Icon Font Stylesheet -->
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css\" rel=\"stylesheet\">
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css\" rel=\"stylesheet\">

    <!-- Libraries Stylesheet -->
    <link href=\"{{asset('client/lib/animate/animate.min.css')}}\" rel=\"stylesheet\">
    <link href=\"{{asset('client/lib/owlcarousel/assets/owl.carousel.min.css')}}\" rel=\"stylesheet\">
    <link href=\"{{asset('client/lib/lightbox/css/lightbox.min.css')}}\" rel=\"stylesheet\">

    <!-- Customized Bootstrap Stylesheet -->
    <link href=\"{{asset('client/css/bootstrap.min.css')}}\" rel=\"stylesheet\">

    <!-- Template Stylesheet -->
    <link href=\"{{asset('client/css/style.css')}}\" rel=\"stylesheet\">
</head>

<body> 
    <!-- Spinner Start -->
    <div id=\"spinner\" class=\"show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center\">
        <div class=\"spinner-border text-primary\" role=\"status\" style=\"width: 3rem; height: 3rem;\"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class=\"container-fluid bg-dark text-light px-0 py-2\">
        <div class=\"row gx-0 d-none d-lg-flex\">
            <div class=\"col-lg-7 px-5 text-start\">
                <div class=\"h-100 d-inline-flex align-items-center me-4\">
                    <span class=\"fa fa-phone-alt me-2\"></span>
                    <span>+216 21 345 678</span>
                </div>
                <div class=\"h-100 d-inline-flex align-items-center\">
                    <span class=\"far fa-envelope me-2\"></span>
                    <span>InnerBloom@gmail.com</span>
                </div>
            </div>
            <div class=\"col-lg-5 px-5 text-end\">
                <div class=\"h-100 d-inline-flex align-items-center mx-n2\">
                    <span>Follow Us:</span>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-facebook-f\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-twitter\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-linkedin-in\"></i></a>
                    <a class=\"btn btn-link text-light\" href=\"\"><i class=\"fab fa-instagram\"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class=\"navbar navbar-expand-lg bg-white navbar-light sticky-top p-0\">
        <a href=\"base.html.twig\" class=\"navbar-brand d-flex align-items-center px-4 px-lg-5\">
            <h1 class=\"m-0\">InnerBloom</h1>
        </a>
        <button type=\"button\" class=\"navbar-toggler me-4\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarCollapse\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\" id=\"navbarCollapse\">
            <div class=\"navbar-nav ms-auto p-4 p-lg-0\">
                <a href=\"base.html.twig\" class=\"nav-item nav-link\">Home</a>
                <a href=\"about.html\" class=\"nav-item nav-link\">About</a>
                <a href=\"service.html\" class=\"nav-item nav-link\">Services</a>
                <a href=\"project.html\" class=\"nav-item nav-link\">Projects</a>
                <div class=\"nav-item dropdown\">
                    <a href=\"#\" class=\"nav-link dropdown-toggle\" data-bs-toggle=\"dropdown\">Pages</a>
                    <div class=\"dropdown-menu bg-light m-0\">
                        <a href=\"feature.html\" class=\"dropdown-item\">Features</a>
                        <a href=\"quote.html\" class=\"dropdown-item\">Free Quote</a>
                        <a href=\"team.html\" class=\"dropdown-item\">Our Team</a>
                        <a href=\"testimonial.html\" class=\"dropdown-item\">Testimonial</a>
                        <a href=\"404.html\" class=\"dropdown-item\">404 Page</a>
                    </div>
                </div>
                <a href=\"contact.html\" class=\"nav-item nav-link\">Contact</a>
            </div>
            <a href=\"\" class=\"btn btn-primary py-4 px-lg-4 rounded-0 d-none d-lg-block\">Login<i class=\"fa fa-arrow-right ms-3\"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class=\"container-fluid p-0 wow fadeIn\" data-wow-delay=\"0.1s\">
        <div id=\"header-carousel\" class=\"carousel slide\" data-bs-ride=\"carousel\">
            <div class=\"carousel-inner\">
                <div class=\"carousel-item active\">
                    <img class=\"w-100\" src=\"{{asset('client/img/carousel-1.jpeg')}}\" alt=\"Image\">
                    <div class=\"carousel-caption\">
                        <div class=\"container\">
                            <div class=\"row justify-content-center\">
                                <div class=\"col-lg-8\">
                                    <h1 class=\"display-1 text-white mb-5 animated slideInDown\">Make your inner well-being bloom</h1>
                                    <a href=\"\" class=\"btn btn-primary py-sm-3 px-sm-4\">Explore More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"carousel-item\">
                    <img class=\"w-100\" src=\"{{asset('client/img/carousel-1.jpeg')}}\" alt=\"Image\">
                    <div class=\"carousel-caption\">
                        <div class=\"container\">
                            <div class=\"row justify-content-center\">
                                <div class=\"col-lg-7\">
                                    <h1 class=\"display-1 text-white mb-5 animated slideInDown\">You deserve to be happy</h1>
                                    <a href=\"\" class=\"btn btn-primary py-sm-3 px-sm-4\">Explore More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#header-carousel\"
                data-bs-slide=\"prev\">
                <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
                <span class=\"visually-hidden\">Previous</span>
            </button>
            <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#header-carousel\"
                data-bs-slide=\"next\">
                <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
                <span class=\"visually-hidden\">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->
{% block body %}

{% endblock %}
    <!-- Footer Start -->
    <div class=\"container-fluid bg-dark text-light footer mt-5 py-5 wow fadeIn\" data-wow-delay=\"0.1s\">
        <div class=\"container py-5\">
            <div class=\"row g-5\">
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Our Office</h4>
                    <p class=\"mb-2\"><i class=\"fa fa-map-marker-alt me-3\"></i>123 Street, Ariana, Tunisia</p>
                    <p class=\"mb-2\"><i class=\"fa fa-phone-alt me-3\"></i>+216 21 345 678</p>
                    <p class=\"mb-2\"><i class=\"fa fa-envelope me-3\"></i>InnerBloom@gmail.com</p>
                    <div class=\"d-flex pt-2\">
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-twitter\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-facebook-f\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-youtube\"></i></a>
                        <a class=\"btn btn-square btn-outline-light rounded-circle me-2\" href=\"\"><i class=\"fab fa-linkedin-in\"></i></a>
                    </div>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Services</h4>
                    <a class=\"btn btn-link\" href=\"\">Landscaping</a>
                    <a class=\"btn btn-link\" href=\"\">Pruning plants</a>
                    <a class=\"btn btn-link\" href=\"\">Urban Gardening</a>
                    <a class=\"btn btn-link\" href=\"\">Garden Maintenance</a>
                    <a class=\"btn btn-link\" href=\"\">Green Technology</a>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Quick Links</h4>
                    <a class=\"btn btn-link\" href=\"\">About Us</a>
                    <a class=\"btn btn-link\" href=\"\">Contact Us</a>
                    <a class=\"btn btn-link\" href=\"\">Our Services</a>
                    <a class=\"btn btn-link\" href=\"\">Terms & Condition</a>
                    <a class=\"btn btn-link\" href=\"\">Support</a>
                </div>
                <div class=\"col-lg-3 col-md-6\">
                    <h4 class=\"text-white mb-4\">Newsletter</h4>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class=\"position-relative w-100\">
                        <input class=\"form-control bg-light border-light w-100 py-3 ps-4 pe-5\" type=\"text\" placeholder=\"Your email\">
                        <button type=\"button\" class=\"btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2\">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class=\"container-fluid copyright py-4\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-md-6 text-center text-md-start mb-3 mb-md-0\">
                    &copy; <a class=\"border-bottom\" href=\"#\">Your Site Name</a>, All Right Reserved.
                </div>
                <div class=\"col-md-6 text-center text-md-end\">
                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from \"https://htmlcodex.com/credit-removal\". Thank you for your support. ***/-->
                    Designed By <a class=\"border-bottom\" href=\"https://htmlcodex.com\">HTML Codex</a> Distributed By <a href=\"https://themewagon.com\">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href=\"#\" class=\"btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top\"><i class=\"bi bi-arrow-up\"></i></a>


    <!-- JavaScript libraries -->
    <script src=\"https://code.jquery.com/jquery-3.4.1.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js\"></script>
    <script src=\"{{asset('client/lib/wow/wow.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/easing/easing.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/waypoints/waypoints.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/owlcarousel/owl.carousel.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/counterup/counterup.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/parallax/parallax.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/isotope/isotope.pkgd.min.js')}}\"></script>
    <script src=\"{{asset('client/lib/lightbox/js/lightbox.min.js')}}\"></script>

    <!-- Template Javascript -->
    <script src=\"{{asset('client/js/main.js')}}\"></script>
</body>

</html>", "base.html.twig", "C:\\Users\\MSI-PC\\innerbloom\\templates\\base.html.twig");
    }
}
