const path = require('path');
const Encore = require('@symfony/webpack-encore');

const syliusBundles = path.resolve(__dirname, 'vendor/sylius/sylius/src/Sylius/Bundle/');
const uiBundleScripts = path.resolve(syliusBundles, 'UiBundle/Resources/private/js/');
const uiBundleResources = path.resolve(syliusBundles, 'UiBundle/Resources/private/');

// Shop config
Encore
  .setOutputPath('public/build/shop/')
  .setPublicPath('/build/shop')
  .addEntry('shop-entry', './assets/shop/entry.js')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
;

const shopConfig = Encore.getWebpackConfig();

shopConfig.resolve.alias['sylius/ui'] = uiBundleScripts;
shopConfig.resolve.alias['sylius/ui-resources'] = uiBundleResources;
shopConfig.resolve.alias['sylius/bundle'] = syliusBundles;
shopConfig.name = 'shop';

Encore.reset();

// Admin config
Encore
  .setOutputPath('public/build/admin/')
  .setPublicPath('/build/admin')
  .addEntry('admin-entry', './assets/admin/entry.js')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
;

const adminConfig = Encore.getWebpackConfig();

adminConfig.resolve.alias['sylius/ui'] = uiBundleScripts;
adminConfig.resolve.alias['sylius/ui-resources'] = uiBundleResources;
adminConfig.resolve.alias['sylius/bundle'] = syliusBundles;
adminConfig.externals = Object.assign({}, adminConfig.externals, { window: 'window', document: 'document' });
adminConfig.name = 'admin';

Encore.reset();

// App config
Encore
  .setOutputPath('public/build/app')
  .setPublicPath('/build/app')
  .addEntry('app-entry', './assets/app/js/app.js')
  .addEntry('app-admin-entry', './assets/app/js/admin/base.js')
  .addEntry('web-page-entry', './assets/app/js/pages/webPage.js')
  .addEntry('home-entry', './assets/app/js/pages/home.js')
  .addEntry('about-entry', './assets/app/js/pages/about.js')
  .addEntry('articles-entry', './assets/app/js/pages/articles.js')
  .addEntry('article-entry', './assets/app/js/pages/article.js')
  .addEntry('contact-entry', './assets/app/js/pages/contact.js')
  
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSassLoader()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[ext]'
  })
;

const appConfig = Encore.getWebpackConfig();
appConfig.name = 'app';

module.exports = [shopConfig, adminConfig, appConfig];
