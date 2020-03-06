## [1.4.2] 2020-03-06

- Fixed a bug where fetching relationships via ajax could end up in a 403 error.

## [1.4.0] 2020-03-03

- Show image thumbnail
- Show image volume name
- Order relationships by element type and entry section/asset volume name
- Links do not open in new tab by default
- Add Relationship button to Globalsets
- Added 'showAllSites' settings (defaults to true)
- Fixed a bug where nothing was shown in element indexes

## [1.3.0] 2020-02-37

- Forked under wsydney76\elementmap
- Added german translations
- Added support for asset edit page
- Added section name to output for entries
- Added draft support
- Show relationships for all sites
- Display relationships in HUD
- Fixed a bug where relations to disabled entries were not shown
- Get maps via ajax

## [1.2.1] 2018-09-27

### Added
- Elements displayed by the map are now sorted in alphabetical order.
- Element map displays disabled elements.

## [1.2.0] 2018-09-12

### Added
- Quick table views for element maps so element counts and references can be viewed on the element overview pages.
- Super Table support.

### Fixed
- Elements displayed within the map will attempt to link to the element within the current site context.

## [1.1.1] 2018-07-20

### Fixed
- Issue with users on multi-site element maps.

## [1.1.0] 2018-07-10

### Added
- Element map display on Craft Commerce 2 products (thanks @nfourtythree).
- Support for products/variants within Craft Commerce 2.

### Fixed
- Proper element multi-site element maps, originally could only show maps for the current site.

### Changed
- Icons now pull directly from SVG files through craft's `svg()` twig function (necessary for non-standard craft icons).
- `resources` folder renamed to `assets` to follow Craft conventions.

## [1.0.2] 2018-01-06

### Fixed
- Further revised quoting and how queries are built to fully address the issue partially addressed by 1.0.1.
- Updated conditions used to traverse matrix blocks for inner related elements, meaning element -> block -> element relationships should now show up properly in the map.

## [1.0.1] 2018-01-04

### Changed
- Minor grammatical updates to README.md

### Fixed
- Quoted column names in joins, which were causing errors when the map was rendered with certain DB configurations.

## [1.0.0] 2017-12-13

The initial release of the Element Map plugin.

### Added
- Element map in full-page editor sidebars, shows elements with relations to the currently edited element
