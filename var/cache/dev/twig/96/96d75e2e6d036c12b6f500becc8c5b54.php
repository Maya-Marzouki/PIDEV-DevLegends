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

/* user/formadduser.html.twig */
class __TwigTemplate_936396aa8938988812e61b9a044e94b1 extends Template
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

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "user/formadduser.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "user/formadduser.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "user/formadduser.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 2
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

        yield " Sign Up ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 3
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

        yield "     
";
        // line 4
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 4, $this->source); })()), 'form_start');
        yield "
<div class=\"container-fluid py-5\">
        <div class=\"container\">
            <div class=\"text-center mx-auto wow fadeInUp\" data-wow-delay=\"0.1s\" style=\"max-width: 500px; visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;\">
                <h1 class=\"display-5 mb-5\">Sign Up</h1>
            </div>
            <div class=\"row justify-content-center\">
                <div class=\"col-lg-7\">
                    <div class=\"bg-light rounded p-4 p-sm-5 wow fadeInUp\" data-wow-delay=\"0.1s\" style=\"visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;\">
                        <div class=\"row g-3\">
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 16
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 16, $this->source); })()), "firstName", [], "any", false, false, false, 16), 'label');
        yield "
                                    ";
        // line 17
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 17, $this->source); })()), "firstName", [], "any", false, false, false, 17), 'widget');
        yield "
                                    ";
        // line 18
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 18, $this->source); })()), "firstName", [], "any", false, false, false, 18), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 23
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 23, $this->source); })()), "lastName", [], "any", false, false, false, 23), 'label');
        yield "
                                    ";
        // line 24
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 24, $this->source); })()), "lastName", [], "any", false, false, false, 24), 'widget');
        yield "
                                    ";
        // line 25
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 25, $this->source); })()), "lastName", [], "any", false, false, false, 25), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 30
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 30, $this->source); })()), "userEmail", [], "any", false, false, false, 30), 'label');
        yield "
                                    ";
        // line 31
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 31, $this->source); })()), "userEmail", [], "any", false, false, false, 31), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "text@gmail.com"]]);
        yield "
                                    ";
        // line 32
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 32, $this->source); })()), "userEmail", [], "any", false, false, false, 32), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 37
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 37, $this->source); })()), "pswrd", [], "any", false, false, false, 37), 'label');
        yield "
                                    ";
        // line 38
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 38, $this->source); })()), "pswrd", [], "any", false, false, false, 38), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "Mdp contenant des chiffres et des lettres"]]);
        yield "
                                    ";
        // line 39
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 39, $this->source); })()), "pswrd", [], "any", false, false, false, 39), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 44
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 44, $this->source); })()), "userRole", [], "any", false, false, false, 44), 'label');
        yield "
                                    ";
        // line 45
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 45, $this->source); })()), "userRole", [], "any", false, false, false, 45), 'widget');
        yield "
                                    ";
        // line 46
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 46, $this->source); })()), "userRole", [], "any", false, false, false, 46), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    ";
        // line 51
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 51, $this->source); })()), "userAge", [], "any", false, false, false, 51), 'label');
        yield "
                                    ";
        // line 52
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 52, $this->source); })()), "userAge", [], "any", false, false, false, 52), 'widget');
        yield "
                                    ";
        // line 53
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 53, $this->source); })()), "userAge", [], "any", false, false, false, 53), 'errors');
        yield "
                                </div>
                            </div>
                            <div class=\"col-12 text-center\">
                                <button class=\"btn btn-primary py-3 px-4\" type=\"submit\">Submit Now</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>      
</div>
";
        // line 65
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 65, $this->source); })()), 'form_end');
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
        return "user/formadduser.html.twig";
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
        return array (  220 => 65,  205 => 53,  201 => 52,  197 => 51,  189 => 46,  185 => 45,  181 => 44,  173 => 39,  169 => 38,  165 => 37,  157 => 32,  153 => 31,  149 => 30,  141 => 25,  137 => 24,  133 => 23,  125 => 18,  121 => 17,  117 => 16,  102 => 4,  87 => 3,  64 => 2,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}
{% block title %} Sign Up {% endblock %}
{% block body %}     
{{ form_start(form) }}
<div class=\"container-fluid py-5\">
        <div class=\"container\">
            <div class=\"text-center mx-auto wow fadeInUp\" data-wow-delay=\"0.1s\" style=\"max-width: 500px; visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;\">
                <h1 class=\"display-5 mb-5\">Sign Up</h1>
            </div>
            <div class=\"row justify-content-center\">
                <div class=\"col-lg-7\">
                    <div class=\"bg-light rounded p-4 p-sm-5 wow fadeInUp\" data-wow-delay=\"0.1s\" style=\"visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;\">
                        <div class=\"row g-3\">
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.firstName)}}
                                    {{form_widget(form.firstName)}}
                                    {{form_errors(form.firstName)}}
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.lastName)}}
                                    {{form_widget(form.lastName)}}
                                    {{form_errors(form.lastName)}}
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.userEmail)}}
                                    {{form_widget(form.userEmail,{attr:{'class':'form-control','placeholder':'text@gmail.com'}})}}
                                    {{form_errors(form.userEmail)}}
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.pswrd)}}
                                    {{form_widget(form.pswrd,{attr:{'class':'form-control','placeholder':'Mdp contenant des chiffres et des lettres'}})}}
                                    {{form_errors(form.pswrd)}}
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.userRole)}}
                                    {{form_widget(form.userRole)}}
                                    {{form_errors(form.userRole)}}
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-floating\">
                                    {{form_label(form.userAge)}}
                                    {{form_widget(form.userAge)}}
                                    {{form_errors(form.userAge)}}
                                </div>
                            </div>
                            <div class=\"col-12 text-center\">
                                <button class=\"btn btn-primary py-3 px-4\" type=\"submit\">Submit Now</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>      
</div>
{{ form_end(form) }}
{% endblock %}", "user/formadduser.html.twig", "C:\\Users\\MSI-PC\\innerbloom\\templates\\user\\formadduser.html.twig");
    }
}
