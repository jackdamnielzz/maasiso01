import type { Schema, Struct } from '@strapi/strapi';

export interface BlocksCta extends Struct.ComponentSchema {
  collectionName: 'components_blocks_ctas';
  info: {
    description: 'Call to action button component';
    displayName: 'CTA';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    label: Schema.Attribute.String & Schema.Attribute.Required;
    openInNewTab: Schema.Attribute.Boolean & Schema.Attribute.DefaultTo<false>;
    style: Schema.Attribute.Enumeration<['primary', 'secondary', 'tertiary']> &
      Schema.Attribute.Required &
      Schema.Attribute.DefaultTo<'primary'>;
    url: Schema.Attribute.String & Schema.Attribute.Required;
  };
}

export interface BlocksImage extends Struct.ComponentSchema {
  collectionName: 'components_blocks_images';
  info: {
    description: 'Image block with caption and alt text';
    displayName: 'Image';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    altText: Schema.Attribute.String & Schema.Attribute.Required;
    caption: Schema.Attribute.String;
    fullWidth: Schema.Attribute.Boolean & Schema.Attribute.DefaultTo<false>;
    image: Schema.Attribute.Media<'images'> & Schema.Attribute.Required;
    maxWidth: Schema.Attribute.String & Schema.Attribute.DefaultTo<'100%'>;
  };
}

export interface BlocksTestimonial extends Struct.ComponentSchema {
  collectionName: 'components_blocks_testimonials';
  info: {
    description: 'Customer testimonial or review block';
    displayName: 'Testimonial';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    authorCompany: Schema.Attribute.String;
    authorImage: Schema.Attribute.Media<'images'>;
    authorName: Schema.Attribute.String & Schema.Attribute.Required;
    authorTitle: Schema.Attribute.String;
    quote: Schema.Attribute.Text & Schema.Attribute.Required;
    rating: Schema.Attribute.Integer &
      Schema.Attribute.SetMinMax<
        {
          max: 5;
          min: 1;
        },
        number
      > &
      Schema.Attribute.DefaultTo<5>;
    style: Schema.Attribute.Enumeration<['card', 'simple', 'featured']> &
      Schema.Attribute.DefaultTo<'card'>;
  };
}

export interface BlocksText extends Struct.ComponentSchema {
  collectionName: 'components_blocks_texts';
  info: {
    description: 'Rich text content block';
    displayName: 'Text';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    alignment: Schema.Attribute.Enumeration<['left', 'center', 'right']> &
      Schema.Attribute.DefaultTo<'left'>;
    content: Schema.Attribute.RichText & Schema.Attribute.Required;
    maxWidth: Schema.Attribute.String & Schema.Attribute.DefaultTo<'100%'>;
  };
}

export interface MetadataSeo extends Struct.ComponentSchema {
  collectionName: 'components_metadata_seos';
  info: {
    description: 'Search Engine Optimization metadata';
    displayName: 'SEO';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    canonicalURL: Schema.Attribute.String;
    metaDescription: Schema.Attribute.Text &
      Schema.Attribute.Required &
      Schema.Attribute.SetMinMaxLength<{
        maxLength: 160;
      }>;
    metaImage: Schema.Attribute.Media<'images'>;
    metaRobots: Schema.Attribute.Enumeration<
      ['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow']
    > &
      Schema.Attribute.Required &
      Schema.Attribute.DefaultTo<'index,follow'>;
    metaTitle: Schema.Attribute.String &
      Schema.Attribute.Required &
      Schema.Attribute.SetMinMaxLength<{
        maxLength: 60;
      }>;
    structuredData: Schema.Attribute.JSON;
  };
}

export interface SectionsHero extends Struct.ComponentSchema {
  collectionName: 'components_sections_heroes';
  info: {
    description: 'Hero section component for page headers';
    displayName: 'Hero';
  };
  options: {
    draftAndPublish: false;
  };
  attributes: {
    backgroundImage: Schema.Attribute.Media<'images'> &
      Schema.Attribute.Required;
    ctaButton: Schema.Attribute.Component<'blocks.cta', false>;
    subtitle: Schema.Attribute.Text;
    title: Schema.Attribute.String & Schema.Attribute.Required;
  };
}

declare module '@strapi/strapi' {
  export module Public {
    export interface ComponentSchemas {
      'blocks.cta': BlocksCta;
      'blocks.image': BlocksImage;
      'blocks.testimonial': BlocksTestimonial;
      'blocks.text': BlocksText;
      'metadata.seo': MetadataSeo;
      'sections.hero': SectionsHero;
    }
  }
}
