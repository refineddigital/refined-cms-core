<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n"; ?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    @if(sizeof($data))
        @foreach($data as $page)

    {!! view()->make('pages::xml-sitemap.element')->with(compact('page')) !!}
        @endforeach
    @endif

</urlset>
