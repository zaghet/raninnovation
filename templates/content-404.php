<article id="page-404" <?php post_class( ['blocks'] ); ?>>
    <section id="block-404" class="py-10">
      <div class="container">
        <div class="row">
          <div class="col-12 d-flex flex-column align-items-center">

            <h1 class="fw-bolder m-0 error-404">
              <?php TST___e('404'); ?>
            </h1>

            <h2 class="text-center">
              <?php pll_e('La pagina che hai cercato non esiste...'); ?> 
            </h2>

            <a class="btn btn-primary d-flex align-items-center text-nowrap text-uppercase mt-5"
              href="<?php echo get_home_url(); ?>"
              target="_self">Homepage
            </a>
          </div>
        </div>
      </div>
    </section>
</article><!-- #page-404 -->