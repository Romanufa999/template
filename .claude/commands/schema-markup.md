# Schema Markup

Expert in structured data and schema.org markup. Goal: implement JSON-LD that enables rich results in search.

## Initial Assessment

If `.claude/product-marketing-context.md` exists, read it first. Then clarify:

1. **Page type** — what content, what rich results are possible?
2. **Current state** — existing schema? errors? which rich results already showing?
3. **Goals** — which rich results to target? business value?

## Core Principles

- **Accuracy first** — schema must match actual page content. Never markup content that doesn't exist.
- **JSON-LD format** — Google-recommended. Place in `<head>` or end of `<body>`.
- **Follow Google's guidelines** — only use markup Google supports. Avoid spam tactics.
- **Validate everything** — test before deploying, monitor Search Console, fix errors promptly.

## Common Schema Types

| Type | Use For | Required Properties |
|------|---------|-------------------|
| Organization | Company homepage/about | name, url |
| WebSite | Homepage (search box) | name, url |
| Article | Blog posts, news | headline, image, datePublished, author |
| Product | Product pages | name, image, offers |
| SoftwareApplication | SaaS/app pages | name, offers |
| FAQPage | FAQ content | mainEntity (Q&A array) |
| HowTo | Tutorials | name, step |
| BreadcrumbList | Any page with breadcrumbs | itemListElement |
| LocalBusiness | Local business pages | name, address |
| Event | Events, webinars | name, startDate, location |

**For complete JSON-LD examples**: See [references/schema-examples.md](references/schema-examples.md)

## Quick Reference

**Organization** — Required: name, url. Recommended: logo, sameAs (social profiles), contactPoint.

**Article/BlogPosting** — Required: headline, image, datePublished, author. Recommended: dateModified, publisher, description.

**Product** — Required: name, image, offers (price + availability). Recommended: sku, brand, aggregateRating, review.

**FAQPage** — Required: mainEntity (array of Question/Answer pairs).

**BreadcrumbList** — Required: itemListElement (array with position, name, item).

## Multiple Schema Types

Combine multiple schema types on one page using `@graph`:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    { "@type": "Organization", ... },
    { "@type": "WebSite", ... },
    { "@type": "BreadcrumbList", ... }
  ]
}
```

## Validation and Testing

Tools:
- **Google Rich Results Test**: https://search.google.com/test/rich-results
- **Schema.org Validator**: https://validator.schema.org/
- **Search Console**: Enhancements reports

Common errors: missing required properties, invalid values (dates must be ISO 8601, URLs fully qualified), mismatch between schema and visible content.

## Implementation by Stack

**Static sites** — add JSON-LD directly in HTML template, use includes/partials for reusable schema.

**Next.js/React** — component that renders schema, server-side rendered for SEO. Serialize data to JSON-LD.

**WordPress/CMS** — plugins (Yoast, Rank Math, Schema Pro), theme modifications, custom fields to structured data.

## Output Format

Provide complete JSON-LD code block, then testing checklist:
- Validates in Rich Results Test
- No errors or warnings
- Matches page content
- All required properties included
