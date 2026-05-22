<?php
/**
 * Built-in shape primitives for Process Mapper step types.
 *
 * Each entry: shape key => default canvas size [w, h] for a step of that shape.
 * Display names are i18n keys: process-mapper.shapes.<key>.
 * Geometry (clip-path / border-radius) lives in assets/css/process-mapper.css
 * as [data-shape="<key>"] rules. The "cloud" shape additionally needs the
 * <clipPath id="pmShapeCloud"> SVG defs block present on the page.
 *
 * This is the single source of truth shared by the Settings page (shape
 * picker) and — from Stage 2 — the editor (default step sizing).
 */
return [
    'rectangle'     => ['w' => 160, 'h' => 80],
    'rounded'       => ['w' => 160, 'h' => 80],
    'pill'          => ['w' => 160, 'h' => 56],
    'circle'        => ['w' => 130, 'h' => 130],
    'diamond'       => ['w' => 150, 'h' => 150],
    'parallelogram' => ['w' => 176, 'h' => 80],
    'trapezoid'     => ['w' => 176, 'h' => 80],
    'hexagon'       => ['w' => 176, 'h' => 90],
    'document'      => ['w' => 160, 'h' => 90],
    'cylinder'      => ['w' => 150, 'h' => 110],
    'cloud'         => ['w' => 188, 'h' => 120],
    'subroutine'    => ['w' => 176, 'h' => 80],
];
