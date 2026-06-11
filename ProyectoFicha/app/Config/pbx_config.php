<?php
/**
 * Configuración de la integración con el PBX Receptor.
 */
return [
    'enabled'      => getenv('PBX_API_ENABLED') !== false ? filter_var(getenv('PBX_API_ENABLED'), FILTER_VALIDATE_BOOLEAN) : true,
    'base_url'     => getenv('PBX_API_URL') ?: 'http://172.16.80.240/api',
    'token'        => getenv('PBX_API_TOKEN') ?: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImQ1NTM1NWUxYTA2MDY2ZjgzNDc1NDdiYmMwMTA4NDhkODE5YzMwYjc4OTYwMDlmNzA1ZmE5ZTdkMjkzMzljM2ZmMDcwY2MzMjQwMzA5ZmI3In0.eyJhdWQiOiJiOGViMzI4ZDNhMTNmZmZlY2VmNTQ1MDUxZmQ0MTMxZjQ4ZTg2NWFlODcyMDZhY2Q4ZGI2MWI2MDE4MzgwNWE1IiwianRpIjoiZDU1MzU1ZTFhMDYwNjZmODM0NzU0N2JiYzAxMDg0OGQ4MTljMzBiNzg5NjAwOWY3MDVmYTllN2QyOTMzOWMzZmYwNzBjYzMyNDAzMDlmYjciLCJpYXQiOjE3NzkxMTg3NjUsIm5iZiI6MTc3OTExODc2NSwiZXhwIjoxNzc5MTIyMzY1LCJzdWIiOiIiLCJzY29wZXMiOlsiZ3FsOmNvcmUiXX0.3stoQzolqeZbojI5gvwZqbU6p5IxD0UtlqwpprKaRESDhzUDuJY2xzWdi2lc6I5dnkZfQZxKZk5-FU06RsJ-yOsR5lMEwQq2bbpb0r7pVMSqLyEVZQCmDCT5bya0b9kxexoOCLkYXyRyEt987Pmd0tIPbq___bgmISvDuieK5FMtp8jmetchOerWt8XFE3bpCWoCAwnUWgrEgmTrGQK-dO5Nqt2M_XqSloV1IdjCvX9_MJq4Uo6pNbqBDBhq0HjDEVjCyGvh9u1EqBPFDBHgemjog6cKQLTw4WI-VLhV33dHAOwmcBCyP46zyLFaJ6yynh1k1mxxV0z97qJefIdATg',
    'timeout'      => getenv('PBX_API_TIMEOUT') ?: 5, // segundos
    'cola_default' => getenv('PBX_COLA_DEFAULT') ?: 'ven911',
];
